<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller blog
 *
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 * @copyrignt
 */
class Controller_Blog_Blog extends Controller_Blog_Template {

	public function action_list()
	{
		$type = $this->request->param('type');

		if( ! $type)
			throw new HTTP_Exception_404();

		$blog_articles = Jelly::query('blog')->show_articles($type)->select();

		$this->template->content = View::factory('frontend/content/blog/list')
			->bind('blog_articles', $blog_articles);
	}

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

} // End Controller_blog