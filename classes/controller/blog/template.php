<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller template
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
abstract class Controller_Blog_Template extends Controller_Template {

	public function before()
	{
		switch($this->request->action())
		{
			case 'new':
			case 'edit':
				$this->_auth_required = TRUE;
				break;
		}

		parent::before();
		StaticCss::instance()->add('css/blog.css');
	}

} // End Controller_Blog_Template