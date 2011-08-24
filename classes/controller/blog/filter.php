<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Controller blog filter
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Toporkov <stopkin0@gmail.com>
 */
class Controller_Blog_Filter extends Controller_Blog_Template {

	public function after()
	{
		if($this->_user == NULL)
		{
			$this->template->content = NULL;
		}
		parent::after();
	}

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

        $filter = Arr::get($_GET, 'filter', 'all');

		$this->template->content = View::factory('frontend/content/blog/filter')
			->bind('current', $filter)
			;
	}

} // End Controller_Blog_Filter