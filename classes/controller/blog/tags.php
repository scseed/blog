<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller blog
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Controller_Blog_Tags extends Controller_Blog_Template {

	/**
	 * Shows blog tags
	 *
	 * @throws HTTP_Exception_404
	 * @return void
	 */
	public function action_list()
	{
//		if( ! $this->_ajax)
//			throw new HTTP_Exception_404();

		$tag_name   = HTML::chars($this->request->param('tag_name'));
		$tag_object = HTML::chars($this->request->param('type'));

		if( ! $tag_name OR !$tag_object)
			throw new HTTP_Exception_404();

		$model_name = Inflector::plural($tag_object).'_tags';
		$objects_tags = Jelly::query($model_name)
			->with('tag')
			->with($tag_object)
			->where(':tag.name', '=', $tag_name)
			->order_by(':tag.name', 'ASC')
			->select();

		$tags_count = count($objects_tags);

		$this->template->content = View::factory('frontend/content/'.$tag_object.'/tags')
			->bind('objects_tags', $objects_tags)
			->bind('tags_count', $tags_count)
			;
	}

	/**
	 * Displays the specified tag blog posts
	 *
	 * @throws HTTP_Exception_404
	 * @return void
	 */
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

} // End Controller_Blog_Tag