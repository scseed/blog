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

//		if( ! $category)
//			throw new HTTP_Exception_404('Blog category is not specified');

		if($id)
		{   // not used. use /article/<id> instead
			$this->_article();
		}
		else
		{
			$this->_list();
		}
	}

	protected function _list()
	{
		$category_name      = HTML::chars($this->request->param('category', NULL));
		$lang               = HTML::chars($this->request->param('lang', NULL));
		$category           = NULL;
		$user_groups        = array();
		$user_id            = NULL;
		$is_allowed_to_post = FALSE;

		$user = $this->_user;

		if(is_array($user))
		{
			$user_id = $user['member_id'];
			$user_groups = '??'; // @TODO remember, how it in ipbwi
		}
		elseif(is_object($user))
		{
			$user_id = $user->id;
			$user_groups = $user->roles->as_array();
		}

		if($category_name)
		{
			$category = Jelly::query('blog_category')
				->where('name', '=', $category_name)
				->active()
				->limit(1)
				->select();
		}
		if($user)
		{
			$is_allowed_to_post = in_array($user_groups, $this->_blog_config->allowed_to_post_groups);
		}

		if( $category AND ! $category->loaded())
			throw new HTTP_Exception_404('There is no such blog category: :category', array(':category' => $category_name));

        if ($category AND $category->is_common)
        {
	        $articles_count = Jelly::query('blog')
		        ->active()
		        ->where('category', '=', $category->id)
		        ->and_where('author_id', '=', $this->_user['member_id'])
		        ->count();
        }
        elseif($category)
        {
	        $articles_count = Jelly::query('blog')
		        ->active()
		        ->where('category', '=', $category->id)
		        ->count();
        }
		else
		{
			$articles_count = Jelly::query('blog')
		        ->active()
		        ->count();
		}

        $page = max(1, arr::get($_GET, 'page', 1));
        $offset = 10 * ($page-1);

        $pager = new Pagination(array(
             'total_items'		=> $articles_count,
              'view'			=> 'pagination/ru'
        ));

        $filter = Arr::get($_GET, 'filter', 'all');
        switch ($filter)
        {
            case 'discussed':
                if ($category->is_common)
                    $articles = Jelly::query('blog')
                        ->join(array('comments', 'c'), LEFT)
                        ->on('blog.id', '=', 'c.object_id')
                        ->where('type_id', '=', 1)
                        ->and_where('lvl', 'is', DB::expr('NULL'))
                        ->and_where('lft', '=', '1')
                        ->and_where('c.text', '=', '-')
                        ->active()
                        ->where('category', '=', $category->id)
                        ->and_where('blogs.author_id', '=', $this->_user['member_id'])
                        ->order_by('rgt', 'desc')
                        ->order_by('date_create', 'DESC')
                        ->limit(10)
                        ->offset($offset)
                        ->select();
                else
                    $articles = Jelly::query('blog')
                        ->join(array('comments', 'c'), LEFT)
                        ->on('blog.id', '=', 'c.object_id')
                        ->where('type_id', '=', 1)
                        ->and_where('lvl', 'is', DB::expr('NULL'))
                        ->and_where('lft', '=', '1')
                        ->and_where('c.text', '=', '-')
                        ->active()
                        ->where('category', '=', $category->id)
                        ->order_by('rgt', 'desc')
                        ->order_by('date_create', 'DESC')
                        ->limit(10)
                        ->offset($offset)
                        ->select();
                break;
            case 'popular':
                if ($category->is_common)
                    $articles = Jelly::query('blog')->select_column(DB::expr('count(l.id)'), 'likes')
                        ->join(array('likes', 'l'), LEFT)
                        ->on('blogs.id', '=', 'l.object_id')
                        ->on('type_id', '=', DB::expr(1))
                        ->active()
                        ->where('category', '=', $category->id)
                        ->and_where('blogs.author_id', '=', $this->_user['member_id'])
                        ->group_by('blogs.id')
                        ->order_by('likes', 'DESC')
                        ->order_by('date_create', 'DESC')
                        ->limit(10)
                        ->offset($offset)
                        ->select();
                else
                    $articles = Jelly::query('blog')->select_column(DB::expr('count(l.id)'), 'likes')
                        ->join(array('likes', 'l'), LEFT)
                        ->on('blogs.id', '=', 'l.object_id')
                        ->on('type_id', '=', DB::expr(1))
                        ->active()
                        ->where('category', '=', $category->id)
                        ->group_by('blogs.id')
                        ->order_by('likes', 'DESC')
                        ->order_by('date_create', 'DESC')
                        ->limit(10)
                        ->offset($offset)
                        ->select();
                break;
            default:
                if ($category AND $category->is_common)
                {
	                $articles = Jelly::query('blog')
		                ->active()
		                ->where('category', '=', $category->id)
		                ->and_where('author_id', '=', $this->_user['member_id'])
		                ->order_by('date_create', 'DESC')
		                ->limit(10)
		                ->offset($offset)
		                ->select();
                }
                elseif($category)
                {
	                $articles = Jelly::query('blog')
		                ->active()
		                ->where('category', '=', $category->id)
		                ->order_by('date_create', 'DESC')
		                ->limit(10)
		                ->offset($offset)
		                ->select();
                }
                else
                {
	                $articles = Jelly::query('blog')
	                    ->with('lang')
		                ->active()
		                ->order_by('date_create', 'DESC')
	                    ->where(':lang.abbr', '=', $lang)
		                ->limit(10)
		                ->offset($offset)
		                ->select();
                }
                break;
        }

		$this->template->title = ($category)
			? $category->title .' / '.__('Блоги')
			: __('Блоги');
		$this->template->content = View::factory('frontend/content/blog/list')
			->bind('articles', $articles)
			->bind('category', $category)
            ->bind('pager', $pager)
            ->bind('is_allowed_to_post', $is_allowed_to_post)
        ;
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