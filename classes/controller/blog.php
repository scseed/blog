<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller blog
 *
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 * @copyrignt
 */
class Controller_Blog extends Controller_Template {

	protected $_tree;

	public function action_index()
	{
		$blogs = Jelly::query('blog')->order_by('date_create', 'DESC')->active()->select();

		$this->page_title = __('Последнее в блогах');
		$this->template->content = View::factory('frontend/content/blog/last')
			->bind('blogs', $blogs);
	}

	public function action_list()
	{
		$type = $this->request->param('type');
		$blog_articles = Jelly::query('blog')->show_articles($type)->select();
		$this->template->content = View::factory('frontend/content/blog/list')
			->bind('blog_articles', $blog_articles);
	}

	public function action_show()
	{
		$type = $this->request->param('type', NULL);
		$id = (int) $this->request->param('id');

		$articles = Jelly::query('blog')->active();

		if($type !== NULL)
		{
			$articles = $articles->where('blog:type.name', '=', HTML::chars($type));
		}
		else
		{
			if( ! $id)
			throw new HTTP_Exception_404();

			$articles = $articles->where('id', '=', $id)->limit(1);
		}

		$articles = $articles->select();

		if($articles instanceof Jelly_Collection)
		{
			$this->template->title = $articles[0]->type->description;
			$this->template->content = View::factory('frontend/content/blog/list')
				->bind('blog_articles', $articles);
		}
		else
		{
			if( ! $articles->loaded())
				throw new HTTP_Exception_404();

			$this->template->title = $articles->title;
			$this->template->content = View::factory('frontend/content/blog/article')
				->bind('article', $articles);
		}
	}

	public function action_comments()
	{
		if( ! $this->_ajax)
			throw new HTTP_Exception_404();

		$blog_id = (int) $this->request->param('id');

		if( ! $blog_id)
			throw new HTTP_Exception_404();

		$comments_root = Jelly::query('blog_comment')
			->where('blog', '=', $blog_id)
			->where('level', '=', 0)
			->limit(1)
			->select();

		if( ! $comments_root->loaded())
		{
			$scope = Jelly::query('blog_comment')->order_by('scope', 'DESC')->limit(1)->select();
			if( ! $scope->loaded())
			{
				$scope = 1;
			}
			else
			{
				$scope = $scope->scope + 1;
			}


			$comments_root = Jelly::factory('blog_comment')
				->set(array(
					'blog' => $blog_id,
					'text' => ' '
				))->save();
			$comments_root->insert_as_new_root($scope);
		}

		$this->template->content = View::factory('frontend/content/blog/comments')
			->set('blog_comments', $comments_root->render_descendants('comments/list'));
	}

	public function action_stats()
	{
		if( ! $this->_ajax)
			throw new HTTP_Exception_404();

		$blog_id = (int) $this->request->param('id');

		if( ! $blog_id)
			throw new HTTP_Exception_404();

		$article = Jelly::query('blog', $blog_id)->select();

		$tags = $article->tags;

		$tags_count = $article->tags->count();

		$this->template->content = View::factory('frontend/content/blog/stats')
			->bind('tags', $tags)
			->bind('tags_count', $tags_count)
			->bind('article', $article)
			;
	}

	public function action_tag()
	{
		$tag_name = HTML::chars($this->request->param('type', NULL));

		if($tag_name == NULL)
			throw new HTTP_Exception_404();

		$tag = Jelly::query('tag')->where('name', '=', $tag_name)->limit(1)->select();

		$blogs = $tag->blogs;

		$this->template->title = $tag->name;
		$this->template->content = View::factory('frontend/content/blog/list')
			->bind('blog_articles', $blogs);
	}

	/**
	 * Loading Textile support
	 *
	 * @return void
	 */
	public function after()
	{
		require_once Kohana::find_file('vendor', 'textile' . DIRECTORY_SEPARATOR . 'textile');

		$this->template->content->textile = new Textile();

		parent::after();
	}

} // End Controller_blog