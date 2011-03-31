<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller blog
 *
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 * @copyrignt
 */
class Controller_Blog_Article extends Controller_Blog_Template {

	public function action_list()
	{
		$type = $this->request->param('type');

		$blog_articles = Jelly::query('blog')->show_articles($type)->select();

		$this->template->content = View::factory('frontend/content/blog/list')
			->bind('blog_articles', $blog_articles);
	}

	public function action_show()
	{
		$id = (int) $this->request->param('id');

		if( ! $id)
		throw new HTTP_Exception_404();

		$article = Jelly::query('blog', $id)->active()->select();

		if( ! $article->loaded())
			throw new HTTP_Exception_404();

		$this->template->title = $article->title;
		$this->template->content = View::factory('frontend/content/blog/article')
			->bind('article', $article);
	}

} // End Controller_blog