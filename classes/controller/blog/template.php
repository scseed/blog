<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller template
 *
 * @package SCSeed
 * @package Blog
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
abstract class Controller_Blog_Template extends Controller_Template {

    protected $admin_group  = 0;
    protected $_blog_config = NULL;

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

		$this->_blog_config = Kohana::$config->load('blog');

		if(defined('ipbwi_BOARD_PATH'))
		{
			include ipbwi_BOARD_PATH.'conf_global.php';
			$this->admin_group = intval($INFO['admin_group']);
            View::set_global('admin_group', $this->admin_group);
		}

		$this->_blog_config = Kohana::$config->load('blog');

		parent::before();
		StaticCss::instance()->add('css/blog.css');
        StaticJs::instance()->add('js/blog.js');
	}

} // End Controller_Blog_Template
