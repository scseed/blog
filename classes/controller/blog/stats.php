<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller blog
 *
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 * @copyrignt
 */
class Controller_Blog_Stats extends Controller_Blog_Template {

	public function action_show()
	{
		if( ! $this->_ajax)
			throw new HTTP_Exception_404();

		$blog_id = (int) $this->request->param('id');

		if( ! $blog_id)
			throw new HTTP_Exception_404();

		$article = Jelly::query('blog', $blog_id)->select();

		$this->template->content = View::factory('frontend/content/blog/stats')
			->bind('article', $article)
			;
	}

} // End Controller_blog