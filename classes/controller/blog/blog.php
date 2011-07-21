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

		if( ! $category)
			throw new HTTP_Exception_404('Blog category is not specified');

		if($id)
		{
			$this->_article();
		}
		else
		{
			$this->_list();
		}
	}

	protected function _list()
	{
		$category_name = HTML::chars($this->request->param('category', NULL));

		$category = Jelly::query('blog_category')
			->where('name', '=', $category_name)
			->limit(1)
			->select();

		if( ! $category->loaded())
			throw new HTTP_Exception_404('There is no such blog category: :category', array(':category' => $category_name));
		
		$articles = Jelly::query('blog')
			->active()
			->where('category', '=', $category->id)
            ->and_where('author_id', '=', $this->_user['member_id'])
			->order_by('date_create', 'DESC')
			->select();

		$this->template->title = $category->title .' / '.__('Блоги');
		$this->template->content = View::factory('frontend/content/blog/list')
			->bind('blog_articles', $articles)
			->bind('category', $category);
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