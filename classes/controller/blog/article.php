<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Template Controller blog
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Controller_Blog_Article extends Controller_Blog_Template {

	public function before()
	{
		parent::before();

		if($this->request->action() == 'new' OR
		   $this->request->action() == 'edit')
		{
/*			StaticCss::instance()
				->add('/js/libs/markitup/markitup/skins/markitup/style.css')
				->add('/js/libs/textile/style.css');*/
			StaticJs::instance()
                    ->add('/js/libs/tiny_mce/tiny_mce.js')
                    ->add('/js/tiny_mce_set.js')
/*				->add('/js/libs/markitup/markitup/jquery.markitup.js')
				->add('/js/libs/textile/set.js')*/
            ;
		}

	}

	/**
	 * Shows blog article
	 *
	 * @throws HTTP_Exception_404
	 * @return void
	 */
	public function action_show()
	{
		$id = (int) $this->request->param('id');

		if( ! $id)
			throw new HTTP_Exception_404();

		$article = Jelly::query('blog', $id)->active()->select();

		if( ! $article->loaded())
			throw new HTTP_Exception_404();

		$comments = Request::factory(
			Route::url('comments', array(
//					'lang'    => I18n::lang(),
					'action'    => 'tree',
					'type'      => 'blog',
					'object_id' => $article->id,
				)
			)
		)->execute()->body();

		$this->template->title = $article->title;
		$this->template->content = View::factory('frontend/content/blog/article')
			->bind('article', $article)
			->bind('comments', $comments)
		;
	}

    /**
     * Moderate article demands to change category
     *
     * @throws HTTP_Exception_404
     * @return void
     */
    public function action_moderate()
    {

        if ($this->_user['member_group_id']!=$this->admin_group)
            throw new HTTP_Exception_404();

        $id = (int) $this->request->param('id');

        if( ! $id) {

            $demands_count = Jelly::query('blog_demand')->where('is_done', '=', '0')->count();
            $page = max(1, arr::get($_GET, 'page', 1));
            $offset = 10 * ($page-1);

            $pager = new Pagination(array(
                 'total_items'		=> $demands_count,
                  'view'			=> 'pagination/ru'
            ));
            $demands = Jelly::query('blog_demand')->where('is_done', '=', '0')->limit(10)->offset($offset)->select();
            $this->template->title = 'Открытые заявки на перенос';
            $this->template->content = View::factory('frontend/content/blog/moderate')
                    ->bind('demands', $demands)->bind('pager', $pager);
        }
        else {
            switch (arr::get($_GET, 'action')) {
                case 'allow':
                    $demand = Jelly::query('blog_demand')->where('blog', '=', $id)->and_where('is_done', '=', 0)->limit(1)->select();
                    $article_id = $demand->blog->id;
                    $category_id = $demand->category->id;
                    $article = Jelly::query('blog', $article_id)->select();
                    if( ! $article->loaded())
                        throw new HTTP_Exception_404();
                    $article->category = $category_id;
                    $article->save();
                    $demand->is_done = 1;
                    $demand->save();
                    break;
                case 'deny':
                    Jelly::query('blog_demand')->where('blog', '=', $id)->and_where('is_done', '=', 0)->limit(1)->select()->delete();
                    break;
                default:
                    break;
            }
            $this->request->redirect(Route::url('blog_article', array('action' => 'moderate')));
        }
    }

    /**
     * Moves article in differ category if admin
     * or makes demand to change category
     * @return void
     */
    public function action_move()
    {

        $id = (int) $this->request->param('id');

        if( ! $id)
            throw new HTTP_Exception_404();

        $article = Jelly::query('blog', $id)->active()->select();

        if( ! $article->loaded())
            throw new HTTP_Exception_404();

        if($this->_user['member_id'] != $article->author->id and $this->_user['member_group_id']!=$this->admin_group)
            throw new HTTP_Exception_401(
                'User with id `:user_id` can\'t edit article with id `:article_id` by author with id `:author_id`',
                array(
                    ':user_id' => $this->_user['member_id'],
                    ':article_id' => $article->id,
                    ':author_id' => $article->author->id
                )
            );

        $categories = Jelly::query('blog_category')->active()->select();

        $errors = NULL;
        /// определить админ или владелец статьи, в зависимости от этого обработка
        if ($this->_user['member_group_id']==$this->admin_group)
        {

            $post = array(
                'article' => array(
                    'category' => $article->category->id,
                ),
            );

            if($this->request->method() === HTTP_Request::POST)
            {
                $article_data = Arr::extract($this->request->post('article'), array_keys($post['article']));
                $article->set($article_data);

                try
                {
                    $article->save();
                }
                catch(Jelly_Validation_Exception $e)
                {
                    $errors = $e->errors('common_validation');
                }
                if ( ! $errors) {
                    Jelly::query('blog_demand')->where('blog', '=', $article->id)
                        ->and_where('is_done', '=', 0)->limit(1)->select()->delete();
                    $this->request->redirect(Route::url('blog_article', array('id' => $article->id)));
                }

                $post['article'] = $article_data;
            }
            $title = 'Перенос статьи в другую категорию';
        }
        else
        {
            $post = array(
                'article' => array(
                    'category' => $article->type->id,
                    'message' => ''
                ),
            );

            if($this->request->method() === HTTP_Request::POST)
            {
                $article_data = Arr::extract($this->request->post('article'), array_keys($post['article']));
                $demand = Jelly::query('blog_demand')->where('blog', '=', $id)
                        ->and_where('is_done', '=', 0)->limit(1)->select();

                /*if ($demand->loaded()) {
                    throw new Exception('Открытая заявка на перенос данной статьи уже существует');
                }*/

                $parser = HTML_parser::factory($article_data['message']);

                foreach(Kohana::config('tags.striptags') as $tag)
                    foreach($parser->find($tag) as $elem)
                        $elem->outertext = '';

                $article_data['message'] = $parser->innertext;
                
                $demand->set( array (
                    'blog' => $id,
                    'category' => $article_data['category'],
                    'message' => $article_data['message'],
                    'is_done' => 0
                ));
                try
                {
                    $demand->save();
                }
                catch(Jelly_Validation_Exception $e)
                {
                    $errors = $e->errors('common_validation');
                }
                if ( ! $errors)
                    $this->request->redirect(Route::url('blog_article', array('id' => $article->id)));

                $post['article'] = $article_data;
            }
            $title = 'Заявка на перенос статьи в другую категорию';
        }
        $this->template->content = View::factory('frontend/form/blog/move')
            ->bind('categories', $categories)
            ->bind('post', $post)
            ->set('title', $title)
            ->set('current_category', $article->category->id)
        ;

    }

    /**
     * Deletes blog article
     * @return void
     */
    public function action_del()
    {
        $id = (int) $this->request->param('id');

        if( ! $id)
            throw new HTTP_Exception_404();

        $article = Jelly::query('blog', $id)->active()->select();

        if( ! $article->loaded())
            throw new HTTP_Exception_404();

        if($this->_user['member_id'] != $article->author->id and $this->_user['member_group_id']!=$this->admin_group)
            throw new HTTP_Exception_401(
                'User with id `:user_id` can\'t edit article with id `:article_id` by author with id `:author_id`',
                array(
                    ':user_id' => $this->_user['member_id'],
                    ':article_id' => $article->id,
                    ':author_id' => $article->author->id
                )
            );
        try {
            $article->delete();
        }
        catch (Exception $e) {
            throw new Database_Exception(-100, 'Delete failed?');
        }

        $this->request->redirect('blog/self');
    }

	public function action_new()
	{
		$category_name = HTML::chars($this->request->param('category'));

		if(! $category_name) $category_name = 'self';
			//throw new HTTP_Exception_404('Category is not defined');

        if ($this->_user['member_group_id']==$this->admin_group)
		    $categories = Jelly::query('blog_category')->active()->admin($this->_user['member_id'])->select();
        else
            $categories = Jelly::query('blog_category')->active()->common($this->_user['member_id'])->select();

		$current_category = NULL;
		foreach($categories as $category)
		{
			if($category->name == $category_name)
			{
				$current_category = $category;
			}
		}

		if( ! $current_category)
			throw new HTTP_Exception_404('Category is not exists');

        if($this->request->method() !== HTTP_Request::POST) {
            $uniq = uniqid();
            Cookie::set_simple('mc_rootpath', $uniq);
            @mkdir('media/content/'.$uniq, 0777, TRUE);
        }

		$post = array(
			'article' => array(
				'category' => NULL,
				'title'    => NULL,
				'text'     => NULL,
			),
			'tags'     => NULL,
		);
		$errors = NULL;

		if($this->request->method() === HTTP_Request::POST)
		{
			$article_data = Arr::extract($this->request->post('article'), array_keys($post['article']));

            $article_data['title'] = HTML::chars($article_data['title']);
            $parser = HTML_parser::factory($article_data['text']);

            foreach(Kohana::config('tags.striptags') as $tag)
                foreach($parser->find($tag) as $elem)
                    $elem->outertext = '';

            $article_data['text'] = $parser->innertext;

			$article_data['author'] = $this->_user['member_id'];

			$article = Jelly::factory('blog');

			$article->set($article_data);
            $article->uniq = Cookie::get_simple('mc_rootpath');

			try
			{
				$article->save();
			}
			catch(Jelly_Validation_Exception $e)
			{
				$errors = $e->errors('common_validation');
			}

			$_tags = explode(',', preg_replace('/([,;][\s]?)/', ',', Arr::get($this->request->post(), 'tags')));

			if(is_string($_tags))
			{
				$_tags[] = $_tags;
			}

			if( ! $errors) {
                
				$this->_save_tags($article, $_tags);
            }

			$post['article'] = $article_data;

			$post['tags'] = implode(',', $_tags);
		}

		$this->template->title = __('New Blog Article');
		$this->template->content = View::factory('frontend/form/blog/new')
			->bind('current_category', $current_category->id)
			->bind('categories', $categories)
			->bind('post', $post)
		;
	}

	public function action_edit()
	{
		$id = (int) $this->request->param('id');

		if( ! $id)
			throw new HTTP_Exception_404();

		$article = Jelly::query('blog', $id)->active()->select();

		if( ! $article->loaded())
			throw new HTTP_Exception_404();

        if($this->_user['member_id'] != $article->author->id and $this->_user['member_group_id']!=$this->admin_group)
			throw new HTTP_Exception_401(
				'User with id `:user_id` can\'t edit article with id `:article_id` by author with id `:author_id`',
				array(
					':user_id' => $this->_user['member_id'],
					':article_id' => $article->id,
					':author_id' => $article->author->id
				)
			);

        Cookie::set_simple('mc_rootpath', $article->uniq);
        @mkdir('media/content/'.$article->uniq, 0777, TRUE);

        if ($this->_user['member_group_id']==$this->admin_group)
		    $categories = Jelly::query('blog_category')->active()->admin($this->_user['member_id'])->select();
        else
            $categories = Jelly::query('blog_category')->active()->common($this->_user['member_id'])->select();

		$post = array(
			'article' => array(
				'category' => $article->category->id,
				'title'    => $article->title,
				'text'     => $article->text,
			),
			'tags'  => implode(', ', Arr::pluck($article->tags->as_array(), 'name')),
		);
		
		$errors = NULL;

		if($this->request->method() === HTTP_Request::POST)
		{
			$article_data = Arr::extract($this->request->post('article'), array_keys($post['article']));

			$article_data['title'] = HTML::chars($article_data['title']);
            $parser = HTML_parser::factory($article_data['text']);

            foreach(Kohana::config('tags.striptags') as $tag)
                foreach($parser->find($tag) as $elem)
                    $elem->outertext = '';

            $article_data['text'] = $parser->innertext;

			$article->set($article_data);

			try
			{
				$article->save();
			}
			catch(Jelly_Validation_Exception $e)
			{
				$errors = $e->errors('common_validation');
			}

			$_tags = explode(',', preg_replace('/([,;][\s]?)/', ',', Arr::get($this->request->post(), 'tags')));

			if(is_string($_tags))
			{
				$_tags[] = $_tags;
			}

			if( ! $errors) {

				$this->_save_tags($article, $_tags);
            }

			$post['article'] = $article_data;

			$post['tags'] = implode(',', $_tags);
		}
        $this->template->title = __('Edit Blog Article');
		$this->template->content = View::factory('frontend/form/blog/edit')
            ->set('current_category', $article->category->id)
			->bind('categories', $categories)
			->bind('post', $post)
			->bind('article_id', $article->id)
		;
	}

    /**
     *
     * Saving post images
     *
     * @param $article_id
     * @return void
     */
    protected function _save_images($article_id) {

//        throw new Exception(print_r($_FILES, TRUE));
        DB::update('images')->set(array('blog_id'=>$article_id))
                ->where('blog_id', '=', 0)
                ->and_where('user_id', '=', $this->_user['member_id'])
                ->execute();
    }
	/**
	 * Saving post tags
	 * 
	 * @param Jelly_Model $article
	 * @param array $_tags
	 * @return void
	 */
	protected function _save_tags(Jelly_Model $article, array $_tags)
	{
		$tags   = array();
		$errors = NULL;
		
		foreach($_tags as $_tag)
		{
			$_tag = HTML::chars(trim($_tag));

			$tag = Jelly::query('tag')->where('name', '=', $_tag)->limit(1)->select();

			if( ! $tag->loaded())
			{
				$tag = Jelly::factory('tag');
				$tag->name = $_tag;

				try
				{
					$tag->save();
				}
				catch(Jelly_Validation_Exception $e)
				{
					break;
				}
			}

			$tags_ids[] = $tag->id;
			$tags[] = $tag;
		}

		if($article->loaded())
		{
			$article->tags = Jelly::query('tag')->where('id', 'IN', $tags_ids)->select();

			try
			{
				$article->save();
			}
			catch(Jelly_Validation_Exception $e)
			{
				$errors = $e->errors('common_validation');
			}
		}

		if(! $errors)
		{
			$this->request->redirect(Route::url('blog_article', array('id' => $article->id)));
		}
	}

} // End Controller_Blog_Article