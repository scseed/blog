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
            case 'carbook':
                $this->_carbook($id);
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
    protected function _carbook($id)
    {
        $this->_list($id, 'category');
    }

    // todo: filters
    protected function _list($id, $name)
    {
        if ($name=='author')
            $articles_count = Jelly::query('blog')->active()
                    ->where($name, '=', $id)
                    ->where(':category.name', '=', 'self')
                    ->count();
        else
            $articles_count = Jelly::query('blog')->active()
                    ->where(':category.car', '=', $id)
                    ->count();

        $page = max(1, arr::get($_GET, 'page', 1));
        $offset = 10 * ($page-1);

        $pager = new Pagination(array(
             'total_items'		=> $articles_count,
              'view'			=> 'pagination/ru'
        ));
        $filter = Arr::get($_GET, 'filter', 'all');
        switch ($filter) {
            case 'discussed':
                if ($name=='author')
                    $articles = Jelly::query('blog')
                            ->join(array('comments', 'c'), LEFT)
                            ->on('blog.id', '=', 'c.object_id')
                            ->where('type_id', '=', 1)
                            ->and_where('lvl', 'is', DB::expr('NULL'))
                            ->and_where('lft', '=', '1')
                            ->and_where('c.text', '=', '-')
                            ->active()->where($name, '=', $id)
                            ->where(':category.name', '=', 'self')
                            ->limit(10)->offset($offset)
                            ->order_by('rgt', 'desc')
                            ->order_by('date_create', 'desc')->select();
                else
                    $articles = Jelly::query('blog')
                            ->join(array('comments', 'c'), LEFT)
                            ->on('blog.id', '=', 'c.object_id')
                            ->where('type_id', '=', 1)
                            ->and_where('lvl', 'is', DB::expr('NULL'))
                            ->and_where('lft', '=', '1')
                            ->and_where('c.text', '=', '-')
                            ->active()->where(':category.car', '=', $id)
                            ->limit(10)->offset($offset)
                            ->order_by('rgt', 'desc')
                            ->order_by('date_create', 'desc')->select();
                break;
            case 'popular':
                if ($name=='author')
                    $articles = Jelly::query('blog')->select_column(DB::expr('count(l.id)'), 'likes')
                            ->join(array('likes', 'l'), LEFT)
                            ->on('blogs.id', '=', 'l.object_id')
                            ->on('type_id', '=', DB::expr(1))
                            ->active()->where($name, '=', $id)
                            ->where(':category.name', '=', 'self')
                            ->limit(10)->offset($offset)
                            ->group_by('blogs.id')
                            ->order_by('likes', 'desc')
                            ->order_by('date_create', 'desc')->select();
                else
                    $articles = Jelly::query('blog')->select_column(DB::expr('count(l.id)'), 'likes')
                            ->join(array('likes', 'l'), LEFT)
                            ->on('blogs.id', '=', 'l.object_id')
                            ->on('type_id', '=', DB::expr(1))
                            ->active()->where(':category.car', '=', $id)
                            ->limit(10)->offset($offset)
                            ->group_by('blogs.id')
                            ->order_by('likes', 'desc')
                            ->order_by('date_create', 'desc')->select();
                break;
            default:
                if ($name=='author')
                    $articles = Jelly::query('blog')->active()->where($name, '=', $id)
                            ->where(':category.name', '=', 'self')
                            ->limit(10)->offset($offset)
                            ->order_by('date_create', 'desc')->select();
                else
                    $articles = Jelly::query('blog')->active()->where(':category.car', '=', $id)
                            ->limit(10)->offset($offset)
                            ->order_by('date_create', 'desc')->select();
                break;
        }
        if ($name=='author') {
            $user = Jelly::query('user', $id)->limit(1)->select();
            $title = 'Личный блог пользователя '.$user->name;
        }
        else {
            $category = Jelly::query('blog_category')->active()->where('car', '=', $id)->limit(1)->select();
            $title = 'Автомобиль '.$category->title;
        }
        
        $this->template->title = $title .' / '.__('Блоги');
        $this->template->content = View::factory('frontend/content/blog/list')
                ->bind('articles', $articles)
                ->bind('pager', $pager)
                ->bind('caption', $title);

    }
} // End Controller_Blog_Blog