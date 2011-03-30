<?php defined('SYSPATH') or die('No direct script access.');


Route::set('blog', 'blog(/<action>(/<type>)(/<id>))', array(
	'id' => '([0-9]*)',
	'type' => '([a-zа-яA-ZА-Я]*)',
))
	->defaults(array(
		'controller' => 'blog',
		'action' => 'show',
		'type' => NULL,
		'id' => NULL,
));