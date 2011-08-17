<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller blog
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Controller_Blog_List extends Controller_Blog_Template {

	/**
	 * Displays the specified blog posts
	 *
	 * @throws HTTP_Exception_404
	 * @return void
	 */
	public function action_show()
	{
		$list_type = HTML::chars($this->request->param('list_type', NULL));

		if( ! method_exists(__CLASS__, $list_type))
			throw new HTTP_Exception_404('There is no such blog list: :blog_list', array(':blog_list' => $list_type));

		$articles = $this->{$list_type}();

		$this->page_title = __($list_type) .' / '.__('Блоги');
		$this->template->content = View::factory('frontend/content/blog/list')
			->bind('blog_articles', $articles)
			->bind('category', $category);
	}

    public function mainpage()
    {
        return Jelly::query('blog')
            ->on_main()
            ->order_by('date_create', 'DESC')
            ->select();
    }

    /**
     * action for route carbooks (prints list of car_books of user_id)
     *
     * @return void
     */

    public function action_carbooks()
    {
        
        $id       = (int) $this->request->param('id');
        if( ! $id)
            throw new HTTP_Exception_404();

        $user = Jelly::query('user', $id)->select();
        if ( ! $user->loaded())
            throw new HTTP_Exception_404();

        $carbooks = Jelly::query('blog')
			->active()
            ->where('author', '=', $id)
            ->and_where(':category.name', '=', 'car_book')
			->order_by('date_create', 'DESC')
			->select();

        $this->template->title = __('User carbooks').$user->name;
        $this->template->content = View::factory('frontend/content/blog/carbooks')
                ->bind('carbooks', $carbooks);
    }


    /**
     * Deletes blog category
     * @return void
     */
    public function action_del()
    {
        if (empty($this->_user['member_id']))
            throw new HTTP_Exception_401();
        
        if ($this->_user['member_group_id']!=$this->admin_group)
            throw new HTTP_Exception_401(); // только админ может удалять блоги

        $id       = (int) $this->request->param('id');
        if( ! $id)
            throw new HTTP_Exception_404();

        $category = Jelly::query('blog_category', $id)->active()->limit(1)->select();
        if ( ! $category->loaded()) {
            throw new HTTP_Exception_404();
        }

        if ($category->is_common) {
            throw new Exception('Нельзя удалять общедоступную категорию');
        }

        $error = FALSE;
        try
        {
            $category->is_active = false;
            $category->save();
        }
        catch (Exception $e)
        {
            $error = TRUE;
        }

        if ( ! $error) {
            DB::update('blogs')->set(array('category_id'=>'2'))->where('category_id', '=', $id)->execute();
            $this->request->redirect(Route::url('blog_action', array('action' => 'list')));
        }
    }

    /**
     * checks data before saving
     * @param  $post_data
     * @return boolean
     */
    protected function _check($post_data, $id = NULL)
    {
        if ($post_data['name']=='car_book')
            return FALSE;
        if (is_null($id)) {     // new
            /*if ($post_data['name']=='car_book') {
                $cnt = Jelly::query('blog_category')
                        ->active()
                        ->where('name', '=', 'car_book')
                        ->and_where('user', '=', $post_data['user'])
                        ->and_where('title', '=', $post_data['title'])
                        ->count();
            }
            else*/ {
                $cnt = Jelly::query('blog_category')
                        ->active()
                        ->where('name', '=', $post_data['name'])
                        ->count();
            }
        }
        else {                  // edit
            /*if ($post_data['name']=='car_book') {
                $cnt = Jelly::query('blog_category')
                        ->active()
                        ->where('name', '=', 'car_book')
                        ->and_where('user', '=', $this->_user['member_id'])
                        ->and_where('title', '=', $post_data['title'])
                        ->and_where('id', '!=', $id)
                        ->count();
            }
            else*/ {
                $cnt = Jelly::query('blog_category')
                        ->active()
                        ->where('name', '=', $post_data['name'])
                        ->and_where('id', '!=', $id)
                        ->count();
            }
        }
        return (boolean) ( ! $cnt);
    }

    /**
     * Creates new blog category ///or car_book
     * @return void
     */
    public function action_new()
    {
        if (empty($this->_user['member_id']))
            throw new HTTP_Exception_401();

        if ($this->_user['member_group_id']!=$this->admin_group)
            throw new HTTP_Exception_401(); // только админ может создать новый тип блога

        $post = array(
            'post' => array(
                /*'cat'=>'car_book',*/
                'name'=>'',
                'title'=>'',
                'description'=>'',
                /*'user'=>$this->_user['member_id'],*/
            )
        );
        if($this->request->method() === HTTP_Request::POST)
        {
            $post_data = Arr::extract($this->request->post('post'), array_keys($post['post']), NULL);
            foreach (array('name', 'title') as $key)
                $post_data[$key] = trim(HTML::chars($post_data[$key]));

            $parser = HTML_parser::factory($post_data['description']);

            foreach(Kohana::config('tags.striptags') as $tag)
                foreach($parser->find($tag) as $elem)
                    $elem->outertext = '';

            $post_data['description'] = $parser->innertext;

            $category = Jelly::factory('blog_category');
            /*
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
            */
            $post_data['is_active'] = TRUE;
            try {
                if ( ! $this->_check($post_data))
                    throw new Exception('Ошибка при сохранении. Нарушена уникальность данных');
                $category->set($post_data)->save();
            }
            catch(Exception $e)
			{
                $error = __($e->getMessage());
			}
            if ( ! $error) {
                $this->request->redirect(Route::url('blog_action', array('action' => 'list')));
            }
            $post['post'] = $post_data;
        }
        //$cat = array('car_book'=>'Новый борт-журнал', 'blog'=>'Новый блог');

        $this->template->title = __('New Blog Category');
        $this->template->content = View::factory('frontend/form/blog/newblog')
        //        ->bind('cat', $cat)
                ->bind('error', $error)
                ->bind('post', $post);
    }

    /**
     * edit blog category or car_book
     * @return void
     */
    public function action_edit()
    {

        if (empty($this->_user['member_id']))
            throw new HTTP_Exception_401();
        
        if ($this->_user['member_group_id']!=$this->admin_group)
            throw new HTTP_Exception_401(); // только админ может править тип блога

        $id       = (int) $this->request->param('id');
        if( ! $id)
            throw new HTTP_Exception_404();

        $category = Jelly::query('blog_category', $id)->active()->limit(1)->select();
        if ( ! $category->loaded()) {
            throw new HTTP_Exception_404();
        }

        if ($category->is_common) {
            throw new Exception('Нельзя модифицировать общедоступную категорию');
        }

        /*if ($this->_user['member_group_id']!=$this->admin_group)
            if ($category->user->id != $this->_user['member_id'])
                throw new HTTP_Exception_401();
*/
        $post = array(
            'post' => array(
//                'cat'=>($category->name=='car_book')? 'car_book': 'blog',
                'name'=>$category->name,
                'title'=>$category->title,
                'description'=>$category->description,
            )
        );
        if($this->request->method() === HTTP_Request::POST)
        {
            $post_data = Arr::extract($this->request->post('post'), array_keys($post['post']), NULL);
            foreach (array('name', 'title') as $key)
                $post_data[$key] = trim(HTML::chars($post_data[$key]));

            $parser = HTML_parser::factory($post_data['description']);

            foreach(Kohana::config('tags.striptags') as $tag)
                foreach($parser->find($tag) as $elem)
                    $elem->outertext = '';

            $post_data['description'] = $parser->innertext;

            try {
                if ( ! $this->_check($post_data, $id))
                    throw new Exception('Ошибка при сохранении. Нарушена уникальность данных');
                $category->set($post_data)->save();
            }
            catch(Exception $e)
			{
                $error = $e->getMessage();
			}
            if ( ! $error) {
                $this->request->redirect(Route::url('blog_action', array('action' => 'list')));
            }
            $post['post'] = $post_data;
        }
        $this->template->title = __('Edit Blog Category');
        //$cat = array('car_book'=>'борт-журнал', 'blog'=>'блог');
        $this->template->content = View::factory('frontend/form/blog/editblog')
                //->bind('cat', $cat)
                ->bind('error', $error)
                ->bind('post', $post)
                ;
    }

    /**
     * shows list of blog categories
     * @throws HTTP_Exception_401
     * @return void
     */
    /// todo: this action
    public function action_list()
    {
        if (empty($this->_user['member_id']))
            throw new HTTP_Exception_401();

        /*if ($this->_user['member_group_id']!=$this->admin_group)
            throw new HTTP_Exception_401(); // только админ может просматривать список блогов
*/
        
        //if ($this->_user['member_group_id']==$this->admin_group)
            $categories = Jelly::query('blog_category')
                    ->active()
                    ->where('is_common', '=', '0')
                    ->select();
        /*else
            $categories = Jelly::query('blog_category')
                    ->active()
                    ->where('is_common', '=', '0')
                    ->and_where('user', '=', $this->_user['member_id'])
                    ->select();
*/
        $this->template->title = __('Category List');
        $this->template->content = View::factory('frontend/content/blog/bloglist')
                ->bind('categories', $categories);
    }
    
} // End Controller_Blog_Blog