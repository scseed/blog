<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller blog
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Controller_Blog_List extends Controller_Blog_Template {

	/**
	 * Displays the specified blog posts
	 *
	 * @throws HTTP_Exception_404
	 * @return void
	 */
	public function action_show()
	{
		$list_type = HTML::chars($this->request->param('list_type', NULL));

		if( ! method_exists(__CLASS__, $list_type))
			throw new HTTP_Exception_404('There is no such blog list: :blog_list', array(':blog_list' => $list_type));

		$articles = $this->{$list_type}();

		$this->page_title = __($list_type) .' / '.__('Блоги');
		$this->template->content = View::factory('frontend/content/blog/list')
			->bind('blog_articles', $articles)
			->bind('category', $category);
	}

    /// todo: доделать этот метод
    /**
     * action for route car_books (prints list of car_books of user_id)
     *
     * @return void
     */
    public function action_carbooks() {
        
        $id = 2;
        print_r(Jelly::query('blog')
			->active()
            ->where('author', '=', $id)
            ->and_where(':category.name', '=', 'car_book')
			->order_by('date_create', 'DESC')
			->select());
    }

	public function mainpage()
	{
		return Jelly::query('blog')
			->on_main()
			->order_by('date_create', 'DESC')
			->select();
	}

} // End Controller_Blog_Blog