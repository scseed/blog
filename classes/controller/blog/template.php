<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller template
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
abstract class Controller_Blog_Template extends Controller_Template {

    protected $admin_group = 0;

	public function before()
	{
		switch($this->request->action())
		{
			case 'new':
			case 'edit':
            case 'del':
            case 'move':
				$this->_auth_required = TRUE;
				break;
		}

        @include ipbwi_BOARD_PATH.'conf_global.php';
        $this->admin_group = intval($INFO['admin_group']);
        View::set_global('admin_group', $this->admin_group);

		parent::before();
		StaticCss::instance()->add('css/blog.css');
	}

} // End Controller_Blog_Template
