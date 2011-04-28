<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller template
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
abstract class Controller_Blog_Template extends Controller_Template {

	/**
	 * Loading Textile support
	 *
	 * @return void
	 */
	public function after()
	{
		require_once Kohana::find_file('vendor', 'textile' . DIRECTORY_SEPARATOR . 'textile');

		if(is_object($this->template->content))
			$this->template->content->textile = new Textile();

		parent::after();
	}

} // End Controller_Blog_Template