<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Controller blog_cars
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Toporkov <stopkin0@gmail.com>
 */
class Controller_Blog_Cars extends Controller_Blog_Template {

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
	 * Displays the specified cars garage of user id
	 *
	 * @return void
	 */
	public function action_list()
	{
        $id = (int) $this->request->param('id');

        if ( ! $id)
            $id = $this->_user['member_id'];

        if ($id != $this->_user['member_id']) {
            $user = Jelly::query('user', $id)->limit(1)->select();
            if ( ! $user->loaded())
                throw new HTTP_Exception_401();
        }
        $cars_count = Jelly::query('car')->active()->where('user', '=', $id)->count();

        $page = max(1, arr::get($_GET, 'page', 1));
        $offset = 20 * ($page-1);

        $pager = new Pagination(array(
             'total_items'		=> $cars_count,
              'items_per_page'  => 20,
              'current_page'    => array
              (
                  'source'		=> 'query_string',
                  'key'         => 'page'
              ),
              'auto_hide'       => TRUE,
              'view'			=> 'pagination/ru'
        ));

        $cars = Jelly::query('car')->active()->where('user', '=', $id)->limit(20)->offset($offset)->select();
        if ($id == $this->_user['member_id'])
            $this->template->title = __('Мой гараж');
        else
            $this->template->title = __('Гараж пользователя').' '.$user->name;
        $this->template->content = View::factory('frontend/content/blog/cars')
                ->bind('cars', $cars)
                ->bind('pager', $pager)
                ->set('my', ($id == $this->_user['member_id']))
                ;
	}

    /**
     * creates new car in garage
     *
     * @return void
     */
    public function action_new()
    {

        $post = array(
            'car' => array(
                'model' => NULL,
                'year'    => NULL,
                'description'     => NULL,
            ),
        );
        $errors = NULL;
        
        $models = Jelly::query('model')->select();
        if($this->request->method() === HTTP_Request::POST)
        {
            $car_data = Arr::extract($this->request->post('car'), array_keys($post['car']));
            $car_data['description'] = HTML_parser::factory($car_data['description'])->plaintext;
            $car_data['user'] = $this->_user['member_id'];
            $car_data['is_active'] = TRUE;
            $car = Jelly::factory('car');
            $car->set($car_data);
            try
            {
                $car->save();
            }
            catch(Jelly_Validation_Exception $e)
            {
                $errors = $e->errors('common_validation');
            }
            $post['car'] = $car_data;
            if (! $errors) {
                $car_book = Jelly::factory('blog_category');
                $car_book_data['name'] = ' ';   // void name to avoid direct access ( via blog/<name>)
                $car_book_data['car'] = $car->id;
                $car_book_data['title'] = $this->_user['members_display_name'].' :: '.$car->model->name.
                                   ' '.$car->year.' (борт-журнал)';
                $car_book_data['is_common'] = FALSE;
                $car_book_data['user'] = $this->_user['member_id'];
                $car_book_data['is_active'] = TRUE;
                $car_book->set($car_book_data);
                $car_book->save();
                $this->request->redirect(Route::url('blog_cars', array('action' => 'list')));
            }
        }
        $this->template->title = __('New Car');
        $this->template->content = View::factory('frontend/form/car/edit')
            ->set('current_model', NULL)
            ->set('action', 'Создать')
            ->bind('models', $models)
            ->bind('post', $post)
            ->bind('errors', $errors)
        ;

    }

    /**
     * edit car in garage
     *
     * @return void
     */
    public function action_edit()
    {
        $id = (int) $this->request->param('id');
        $car = Jelly::query('car', $id)->active()->select();

        if( ! $car->loaded())
            throw new HTTP_Exception_404();

        if($this->_user['member_id'] != $car->user->id and $this->_user['member_group_id']!=$this->admin_group)
            throw new HTTP_Exception_401(
                'User with id `:user_id` can\'t edit car with id `:article_id` by author with id `:author_id`',
                array(
                    ':user_id' => $this->_user['member_id'],
                    ':article_id' => $car->id,
                    ':author_id' => $car->user->id
                )
            );

        $post = array(
            'car' => array(
                'model' => $car->model,
                'year'    => $car->year,
                'description'     => $car->description,
            ),
        );
        $errors = NULL;

        $models = Jelly::query('model')->select();
        if($this->request->method() === HTTP_Request::POST)
        {
            $car_data = Arr::extract($this->request->post('car'), array_keys($post['car']));
            $car_data['description'] = HTML_parser::factory($car_data['description'])->plaintext;
            $car->set($car_data);
            try
            {
                $car->save();
            }
            catch(Jelly_Validation_Exception $e)
            {
                $errors = $e->errors('common_validation');
            }
            if (! $errors) {
                $this->request->redirect(Route::url('blog_cars', array('action' => 'list')));
            }
            $post['car'] = $car_data;
        }

        $this->template->title = __('Edit Car');
        $this->template->content = View::factory('frontend/form/car/edit')
                ->set('current_model', $car->model->id)
                ->set('action', 'Сохранить')
                ->bind('models', $models)
                ->bind('post', $post)
                ->bind('errors', $errors)
        ;

    }

    /**
     * deletes car
     *
     * @return void
     */
    public function action_del()
    {
        $id = (int) $this->request->param('id');
        $car = Jelly::query('car', $id)->active()->select();

        if( ! $car->loaded())
            throw new HTTP_Exception_404();

        if($this->_user['member_id'] != $car->user->id and $this->_user['member_group_id']!=$this->admin_group)
            throw new HTTP_Exception_401(
                'User with id `:user_id` can\'t edit car with id `:article_id` by author with id `:author_id`',
                array(
                    ':user_id' => $this->_user['member_id'],
                    ':article_id' => $car->id,
                    ':author_id' => $car->user->id
                )
            );
        try {
            $car->is_active = FALSE;
            $car->save();
            //$car->delete();
        }
        catch (Exception $e) {
            throw new Database_Exception(-100, 'Delete failed?');
        }
        
        $category = Jelly::query('blog_category')->where('car', '=', $id)->limit(1)->select();
        if ($category->loaded()) {
            DB::update('blogs')->set(array('category_id'=>'2'))->where('category_id', '=', $category->id)->execute();
        }
        $this->request->redirect(Route::get('blog_cars')->uri(array('action' => 'list')));

    }

    /**
     * shows car info
     *
     * @return void
     */
    /*public function action_show()
    {
        
    }
*/
    
    /**
     * shows car gallery
     * 
     * @return void
     */
    public function action_gallery()
    {

    }

    /**
     * shows carbook
     *
     * @return void
     */
    public function action_journal()
    {
        $id = (int) $this->request->param('id');

        if( ! $id)
            throw new HTTP_Exception_404();

        $category = Jelly::query('blog_category')->active()->where('car', '=', $id)->limit(1)->select();
        if ( ! $category->loaded()) {
            throw new HTTP_Exception_404();
        }
        $articles_count = Jelly::query('blog')->active()->where('category', '=', $category->id)->count();
        
        $page = max(1, arr::get($_GET, 'page', 1));
        $offset = 20 * ($page-1);

        $pager = new Pagination(array(
             'total_items'		=> $articles_count,
              'items_per_page'  => 20,
              'current_page'    => array
              (
                  'source'		=> 'query_string',
                  'key'         => 'page'
              ),
              'auto_hide'       => TRUE,
              'view'			=> 'pagination/ru'
        ));

        $articles = Jelly::query('blog')->active()->where('category', '=', $category->id)
                ->limit(20)
                ->offset($offset)
                ->order_by('date_create', 'DESC')
                ->select();

        $this->template->title = $category->title;
        $this->template->content = View::factory('frontend/content/blog/carbooks')
                ->bind('carbooks', $articles)
                ->bind('pager', $pager)
                ;
    }
} // End Controller_Blog_Cars