<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller blog
 *
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 * @copyrignt
 */
class Controller_Blog_Tag extends Controller_Blog_Template {

	public function action_tree()
	{
		if( ! $this->_ajax)
			throw new HTTP_Exception_404();

		$blog_id = (int) $this->request->param('id');

		if( ! $blog_id)
			throw new HTTP_Exception_404();

		$tags = Jelly::query('blog_tag')->where('blog', '=', $blog_id)->select();

		$tags_count = $tags->count();

		$this->template->content = View::factory('frontend/content/blog/tags')
			->bind('tags', $tags)
			->bind('tags_count', $tags_count)
			;
	}

	public function action_show()
	{
		$tag_name = HTML::chars($this->request->param('tag_name', NULL));

		if($tag_name == NULL)
			throw new HTTP_Exception_404();

		$tag = Jelly::query('tag')->where('name', '=', $tag_name)->limit(1)->select();

		$blogs = $tag->blogs;

		$this->template->title = $tag->name;
		$this->template->content = View::factory('frontend/content/blog/list')
			->bind('blog_articles', $blogs);
	}

} // End Controller_blog