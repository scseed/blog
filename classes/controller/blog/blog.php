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
	 * Shows list of blogs articles by there destination (ie mainpage)
	 *
	 * @throws HTTP_Exception_404
	 * @return void
	 */
	public function action_list()
	{
		$type = $this->request->param('type');

		if( ! $type)
			throw new HTTP_Exception_404();

		$blog_articles = Jelly::query('blog')->show_articles($type)->select();

		$this->template->content = View::factory('frontend/content/blog/list')
			->bind('blog_articles', $blog_articles);
	}

	/**
	 * Displays the specified blog posts
	 *
	 * @throws HTTP_Exception_404
	 * @return void
	 */
	public function action_show()
	{
		$type = $this->request->param('type', NULL);

		if( ! $type)
			throw new HTTP_Exception_404();

		$articles = Jelly::query('blog')->active()->where('blog:type.name', '=', HTML::chars($type))->select();

		$this->template->title = $articles[0]->type->description;
		$this->template->content = View::factory('frontend/content/blog/list')
			->bind('blog_articles', $articles);
	}

	/**
	 * Displays blog name
	 *
	 * @throws HTTP_Exception_404
	 * @return HTML::anchor()
	 */
	public function action_name()
	{
		$id = $this->request->param('id', NULL);
		$type = $this->request->param('type', NULL);

		if( ! $id)
		{
			if( ! $type)
				throw new HTTP_Exception_404();

			$blog_type = Jelly::query('blog_type')->where('name', '=', HTML::chars($type))->limit(1)->select();
		}
		else
		{
			$blog = Jelly::query('blog', (int) $id)->select();
			$blog_type = $blog->type;
		}

		$this->template->content = View::factory('frontend/content/blog/name')->bind('blog_type', $blog_type);

	}

} // End Controller_Blog_Blog