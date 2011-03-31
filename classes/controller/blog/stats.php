<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller blog
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Controller_Blog_Stats extends Controller_Blog_Template {

	/**
	 * Shows blog stats
	 *
	 * @throws HTTP_Exception_404
	 * @return void
	 */
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

} // End Controller_Blog_Stats