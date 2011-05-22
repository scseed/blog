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

		parent::after();
	}

} // End Controller_Blog_Template