<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Controller blog_articles
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Toporkov <stopkin0@gmail.com>
 */
class Controller_Blog_Articles extends Controller_Blog_Template {

	/**
	 * Displays the specified blog posts
	 *
	 * @throws HTTP_Exception_404
	 * @return void
	 */
	public function action_show()
	{
		$category = HTML::chars($this->request->param('category', NULL));
        $id = (int) $this->request->param('id', NULL);

        if ( ! $id)
            throw new HTTP_Exception_404();

        switch ($category) {

            // статьи пользователя
            case 'self':
                $this->_self($id);
                break;

            // статьи борт-журнала с категорией id
            case 'car_book':
                $this->_car_book($id);
                break;
            default:
                throw new HTTP_Exception_404();
                break;
        }
	}

    /**
     * Displays list of articles of user $id
     *
     * @param  $id
     * @return void
     */
    protected function _self($id)
    {
        $this->_list($id, 'author');
    }

    /**
     * Displays list of car_books of car $id
     *
     * @param  $id
     * @return void
     */
    protected function _car_book($id)
    {
        $this->_list($id, 'category');
    }

    protected function _list($id, $name)
    {
        $articles_count = Jelly::query('blog')->active()->where($name, '=', $id)->count();

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

        $articles = Jelly::query('blog')->active()->where($name, '=', $id)->limit(20)->offset($offset)->select();
        if ($name=='author') {
            $user = Jelly::query('user', $id)->limit(1)->select();
            $title = 'Личный блог пользователя '.$user->name;
        }
        else {
            $category = Jelly::query('blog_category', $id)->active()->limit(1)->select();
            $title = 'Борт-журнал автомобиля '.$category->title;
        }
        
        $this->template->title = $title .' / '.__('Блоги');
        $this->template->content = View::factory('frontend/content/blog/articles')
                ->bind('articles', $articles)
                ->bind('pager', $pager)
                ->bind('caption', $title);

    }
} // End Controller_Blog_Blog