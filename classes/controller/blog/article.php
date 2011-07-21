<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Template Controller blog
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Controller_Blog_Article extends Controller_Blog_Template {

    protected $admin_group = 0;

	public function before()
	{
		parent::before();

		if($this->request->action() == 'new' OR
		   $this->request->action() == 'edit')
		{
			StaticCss::instance()
				->add('/js/libs/markitup/markitup/skins/markitup/style.css')
				->add('/js/libs/textile/style.css');
			StaticJs::instance()
				->add('/js/libs/markitup/markitup/jquery.markitup.js')
				->add('/js/libs/textile/set.js');
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
		$comments = Request::factory(Route::get('comments')->uri(array(
				'action' => 'tree',
				'object_id' => $article->id,
				'visibility' => ($article->allow_comments) ? 'show' : 'hide'
			)))->execute()->body();

		$this->template->title = $article->title;
		$this->template->content = View::factory('frontend/content/blog/article')
			->bind('article', $article)
			->bind('comments', $comments)
		;
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

        if($this->_user['member_id'] != $article->author->id or $this->_user['member_group_id']!=$this->admin_group)
            throw new HTTP_Exception_401(
                'User with id `:user_id` can\'t edit article with id `:article_id` by author with id `:author_id`',
                array(
                    ':user_id' => $this->_user['member_id'],
                    ':article_id' => $article->id,
                    ':author_id' => $article->author->id
                )
            );

        /// todo: определить админ или владелец статьи, в зависимости от этого обработка
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

        if($this->_user['member_id'] != $article->author->id or $this->_user['member_group_id']!=$this->admin_group)
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

		$categories = Jelly::query('blog_category')->select();

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

			$article_data['text'] = HTML::chars($article_data['text']);

			$article_data['author'] = $this->_user['member_id'];

			$article = Jelly::factory('blog');

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

			if( ! $errors)
				$this->_save_tags($article, $_tags);

			$post['article'] = $article_data;

			$post['tags'] = implode(',', $_tags);
		}

		$this->template->page_title = __('New Blog Article');
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

        if($this->_user['member_id'] != $article->author->id or $this->_user['member_group_id']!=$this->admin_group)
			throw new HTTP_Exception_401(
				'User with id `:user_id` can\'t edit article with id `:article_id` by author with id `:author_id`',
				array(
					':user_id' => $this->_user['member_id'],
					':article_id' => $article->id,
					':author_id' => $article->author->id
				)
			);

		$categories = Jelly::query('blog_category')->select();

		$post = array(
			'article' => array(
				'category' => $article->type->id,
				'title'    => $article->title,
				'text'     => $article->text,
			),
			'tags'  => implode(', ', Arr::pluck($article->tags->as_array(), 'name')),
		);
		
		$errors = NULL;

		if($this->request->method() === HTTP_Request::POST)
		{
			$article_data = Arr::extract($this->request->post('article'), array_keys($post['article']));

			$article_data['text'] = HTML::chars($article_data['text']);

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

			if( ! $errors)
				$this->_save_tags($article, $_tags);

			$post['article'] = $article_data;

			$post['tags'] = implode(',', $_tags);
		}

		$this->template->content = View::factory('frontend/form/blog/edit')
			->bind('categories', $categories)
			->bind('post', $post)
			
		;
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
			$this->request->redirect(Route::url('blog', array('category' => $article->category->name, 'id' => $article->id)));
		}
	}

} // End Controller_Blog_Article