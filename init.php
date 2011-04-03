<?php defined('SYSPATH') or die('No direct script access.');


Route::set('blog', 'blog(/<action>(/<type>)(/<id>))', array(
	'id' => '([0-9]*)',
	'type' => '([a-z]*)',
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'blog',
		'action' => 'show',
		'type' => NULL,
		'id' => NULL,
));
Route::set('blog_article', 'article(/<action>(/<id>))', array(
	'id' => '([0-9]*)',
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'article',
		'action' => 'show',
		'id' => NULL,
));
Route::set('blog_tag', 'tag(/<action>(/<tag_name>)(/<id>))', array(
	'tag_name' => '([a-zа-яA-ZА-Я]*)',
	'id' => '([0-9]*)',
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'tag',
		'action' => 'tree',
));
Route::set('blog_stats', 'stats/<action>/<id>')
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'stats',
		'action' => 'show',
));
Route::set('blog_comment', 'comment(/<action>(/<id>(/<place>)))', array(
	'place' => '(inside|next)'
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'comment',
		'action' => 'tree',
));