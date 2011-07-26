<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller blog
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */

class Controller_Blog_Blog extends Controller_Blog_Template {

	/**
	 * Displays the specified blog posts
	 *
	 * @throws HTTP_Exception_404
	 * @return void
	 */
	public function action_show()
	{
		$category = $this->request->param('category', NULL);
		$id       = (int) $this->request->param('id');

		if( ! $category)
			throw new HTTP_Exception_404('Blog category is not specified');

		if($id)
		{   // not used. use /article/<id> instead
			$this->_article();
		}
		else
		{
			$this->_list();
		}
	}

    /**
     * Deletes blog category
     * @return void
     */
    public function action_del()
    {
        if ($this->_user['member_group_id']!=$this->admin_group)
            throw new HTTP_Exception_401(); // только админ может удалять блоги

        $id = (int) $_GET['id'];
        if( ! $id)
            throw new HTTP_Exception_404();

        $category = Jelly::factory('blog_category', $id);
        if ( ! $category->loaded()) {
            throw new HTTP_Exception_404();
        }

        if ($category->is_common) {
            throw new Exception('Нельзя удалять общедоступную категорию');
        }

        $error = FALSE;
        try
        {
            $category->delete();
        }
        catch (Exception $e)
        {
            $error = TRUE;
        }

        if ( ! $error) {
            DB::update('blogs')->set(array('category_id'=>'2'))->where('category_id', '=', $id)->execute();
            $this->request->redirect(Route::url('blog_list', array('list_type' => 'mainpage')));
        }
    }

    /// todo: во вьюхах сделать js чтобы при выборе car_book имя становилось car_book
    /// todo: при сохранении проверять уникальность поля name (если это не car_book)
    /// todo: запрет имен self, car_book
    /**
     * Creates new blog category or car_book
     * @return void
     */
    public function action_new()
    {
        $post = array(
            'post' => array(
                'cat'=>'car_book',
                'name'=>'car_book',
                'title'=>'',
                'description'=>'',
                'user'=>$this->_user['member_id'],
            )
        );
        if($this->request->method() === HTTP_Request::POST)
        {
            $post_data = Arr::extract($this->request->post('post'), array_keys($post['post']), NULL);
            foreach (array('name', 'title', 'description') as $key)
                $post_data[$key] = HTML_parser::factory($post_data[$key])->plaintext;
            $category = Jelly::factory('blog_category');
            if ($post_data['cat']=='blog')
            {
                if ($this->_user['member_group_id']!=$this->admin_group)
                    throw new HTTP_Exception_401(); // только админ может создать новый тип блога
                $post_data['user'] = NULL;
            }
            elseif ($post_data['cat']=='car_book')
            {
                $post_data['name'] = 'car_book';
            }
            try {
                $category->set($post_data)->save();
            }
            catch(Jelly_Validation_Exception $e)
			{
				$errors = $e->errors('common_validation');
			}
            if ( ! $errors) {
                $this->request->redirect(Route::url('blog_list', array('list_type' => 'mainpage')));

            }
            $post['post'] = $post_data;
        }
        $cat = array('car_book'=>'Новый борт-журнал', 'blog'=>'Новый блог');
        $this->template->content = View::factory('frontend/form/blog/newblog')
                ->bind('cat', $cat)
                ->bind('errors', $errors)
                ->bind('post', $post);
    }

    /**
     * edit blog category or car_book
     * @return void
     */
    public function action_edit()
    {

        $id = (int) $_REQUEST['id'];
        if( ! $id)
            throw new HTTP_Exception_404();

        $category = Jelly::factory('blog_category', $id);
        if ( ! $category->loaded()) {
            throw new HTTP_Exception_404();
        }

        if ($category->is_common) {
            throw new Exception('Нельзя модифицировать общедоступную категорию');
        }

        if ($this->_user['member_group_id']!=$this->admin_group)
            if ($category->user->id != $this->_user['member_id'])
                throw new HTTP_Exception_401();

        $post = array(
            'post' => array(
                'name'=>$category->name,
                'title'=>$category->title,
                'description'=>$category->description,
            )
        );
        if($this->request->method() === HTTP_Request::POST)
        {
            $post_data = Arr::extract($this->request->post('post'), array_keys($post['post']), NULL);
            foreach (array('name', 'title', 'description') as $key)
                $post_data[$key] = HTML_parser::factory($post_data[$key])->plaintext;

            try {
                $category->set($post_data)->save();
            }
            catch(Jelly_Validation_Exception $e)
			{
				$errors = $e->errors('common_validation');
			}
            if ( ! $errors) {
                $this->request->redirect(Route::url('blog_list', array('list_type' => 'mainpage')));

            }
            $post['post'] = $post_data;
        }
        $this->template->content = View::factory('frontend/form/blog/editblog')
                ->bind('errors', $errors)
                ->bind('post', $post)
                ->bind('id', $id);
    }

	protected function _list()
	{
		$category_name = HTML::chars($this->request->param('category', NULL));

		$category = Jelly::query('blog_category')
			->where('name', '=', $category_name)
			->limit(1)
			->select();

		if( ! $category->loaded())
			throw new HTTP_Exception_404('There is no such blog category: :category', array(':category' => $category_name));
		
		$articles = Jelly::query('blog')
			->active()
			->where('category', '=', $category->id)
            ->and_where('author_id', '=', $this->_user['member_id'])
			->order_by('date_create', 'DESC')
			->select();

		$this->template->title = $category->title .' / '.__('Блоги');
		$this->template->content = View::factory('frontend/content/blog/list')
			->bind('blog_articles', $articles)
			->bind('category', $category);
	}

	/**
	 * Shows blog article for its owner
	 *
	 * @throws HTTP_Exception_404
	 * @return void
	 */
	protected function _article()
	{
		$id = (int) $this->request->param('id');

		if( ! $id)
			throw new HTTP_Exception_404();

		$article = Jelly::query('blog', $id)->where('author_id', '=', $this->_user['member_id'])->active()->select();

		if( ! $article->loaded())
			throw new HTTP_Exception_404();

		$comments = Request::factory(
				Route::url('comments', array(
						'lang'    => I18n::lang(),
						'action'    => 'tree',
						'type'      => 'blog',
						'object_id' => $article->id,
					)
				)
			)->execute()->body();

		$tags = Request::factory(
			Route::get('tags')->uri(array(
				'type' => 'blog',
				'object_id' => $article->id
			)))->execute()->body();

		$this->template->title = $article->title;
		$this->template->content = View::factory('frontend/content/blog/article')
			->bind('article', $article)
			->bind('comments', $comments)
			->bind('tags', $tags)
		;
	}



} // End Controller_Blog_Blog