<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Controller blog_cars
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Toporkov <stopkin0@gmail.com>
 */
class Controller_Blog_Cars extends Controller_Blog_Template {

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

    }

    /**
     * edit car in garage
     *
     * @return void
     */
    public function action_edit()
    {

    }

    /**
     * deletes car
     *
     * @return void
     */
    public function action_del()
    {
        $id = (int) $this->request->param('id');

        if( ! $id)
            throw new HTTP_Exception_404();

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

        $this->template->content = View::factory('frontend/content/blog/carbooks2')
                ->bind('articles', $articles)
                ->bind('pager', $pager)
                ;
    }
} // End Controller_Blog_Cars