<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Load language conf
 */
$langs = Page::instance()->system_langs();

Route::set('blog', '(<lang>/)blog/<category>(/<id>)(/<action>)', array(
	'lang'       => $langs,
	'category' => '[\w_]+',
	'id'       => '\d+',
	'action'   => '\w+'
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'blog',
		'action' => 'show',
		'category' => NULL,
));

Route::set('blog_article', '(<lang>/)article(/<action>(/<category>)(/<id>))', array(
	'lang'     => $langs,
	'id'       => '\d+',
	'category' => '[^\d][\w_]+',
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'article',
		'action' => '',
));
Route::set('blog_stats', '(<lang>/)stats/<action>/<id>', array(
	'lang'       => $langs,
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'stats',
		'action' => 'show',
));
Route::set('tags', '(<lang>/)tags(/<type>)/<object_id>', array(
	'lang'      => $langs,
	'type'      => '\w+',
	'object_id' => '[-_\w]+',
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'tags',
		'action' => 'list',
));