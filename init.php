<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Load language conf
 */
$langs = Controller_Page::langs();

Route::set('blog', '(<lang>/)blog(/<action>(/<type>)(/<id>))', array(
	'lang'       => $langs,
	'id' => '([0-9]*)',
	'type' => '([_a-z0-9]*)',
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'blog',
		'action' => 'show',
		'type' => NULL,
		'id' => NULL,
));
Route::set('blog_article', '(<lang>/)article(/<action>(/<id>))', array(
	'lang'       => $langs,
	'id' => '([0-9]*)',
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'article',
		'action' => 'show',
		'id' => NULL,
));
Route::set('blog_tag', '(<lang>/)tag(/<action>(/<tag_name>)(/<id>))', array(
	'lang'       => $langs,
	'tag_name' => '([a-zа-яA-ZА-Я]*)',
	'id' => '([0-9]*)',
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'tag',
		'action' => 'tree',
));
Route::set('blog_stats', '(<lang>/)stats/<action>/<id>', array(
	'lang'       => $langs,
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'stats',
		'action' => 'show',
));