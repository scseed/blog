<?php defined('SYSPATH') or die('No direct script access.');

$langs = NULL;
if(class_exists('Page'))
{
	/**
	 * Load language conf
	 */
	$langs = Page::instance()->system_langs();
}

$blog_list_methods = get_class_methods('Controller_Blog_List');

foreach($blog_list_methods as $blog_list_method)
{
	if(
		$blog_list_method != 'action_show' AND
		$blog_list_method != 'before' AND
		$blog_list_method != 'after' AND
		$blog_list_method != '__construct'
	)
	$blog_lists[] = $blog_list_method;
}

$blog_lists = '('.implode($blog_lists, '|').')';

Route::set('car_books', '(<lang>/)car_books/<id>', array(
	'lang'     => $langs,
    'id'       => '\d+',
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'list',
		'action' => 'carbooks',
));

Route::set('blog_list', '(<lang>/)blogs/<list_type>', array(
	'lang'     => $langs,
	'list_type' => $blog_lists,
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'list',
		'action' => 'show',
));

Route::set('article_list', '(<lang>/)articles/<category>/<id>', array(
	'lang'     => $langs,
    'category' => '[\w_]+',
    'id'       => '\d+',
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'articles',
		'action' => 'show',
));

/*
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
*/

Route::set('blog', '(<lang>/)blog(/<action>)(/<category>)', array(
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

Route::set('blog_article', '(<lang>/)article(/<id>)(/<action>)', array(
	'lang'     => $langs,
	'id'       => '\d+',
	'category' => '[^\d][\w_]+',
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'article',
		'action' => 'show',
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