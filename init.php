<?php defined('SYSPATH') or die('No direct script access.');

$langs = '[a-z]{2}';

$blog_list_methods = get_class_methods('Controller_Blog_List');

foreach($blog_list_methods as $blog_list_method)
{
	if(
		$blog_list_method != 'action_show' AND
        $blog_list_method != 'action_list' AND
        $blog_list_method != 'action_edit' AND
        $blog_list_method != 'action_del' AND
        $blog_list_method != 'action_new' AND
		$blog_list_method != 'before' AND
		$blog_list_method != 'after' AND
		$blog_list_method != '__construct'
	)
	$blog_lists[] = $blog_list_method;
}

$blog_lists = '('.implode($blog_lists, '|').')';

Route::set('car_books', '(<lang>/)carbooks/<id>', array(
	'lang'     => $langs,
    'id'       => '\d+',
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'list',
		'action' => 'carbooks',
));

Route::set('blog_action', '(<lang>/)blogs/<action>(/<id>)', array(
    'lang'     => $langs,
    'action' => '(new|del|edit|list|activity|author|comments)',
    'id'       => '\d+',
))
    ->defaults(array(
        'directory' => 'blog',
        'controller' => 'list',
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

Route::set('blog_cars', '(<lang>/)cars/<action>(/<id>)', array(
	'lang'     => $langs,
    'action'   => '[\w_]+',
    'id'       => '\d+',
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'cars',
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


Route::set('blog', '(<lang>/)blog(/<category>)(/<id>)(/<action>)', array(
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

/*
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
*/
Route::set('blog_article', '(<lang>/)article(/<category>)(/<id>)(/<action>)', array(
	'lang'     => $langs,
	'id'       => '\d+',
	'category' => '([a-zA-Z_]?)',
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

Route::set('blog_filter', '(<lang>/)filter/<action>', array(
	'lang'       => $langs,
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'filter',
		'action' => 'show',
));

Route::set('blog_images', '(<lang>/)images/<action>(/<id>)', array(
	'lang'       => $langs,
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'images',
		'action' => 'show',
        'id' => NULL
));

Route::set('blog_members', '(<lang>/)members/<action>(/<id>)', array(
	'lang'       => $langs,
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'members',
		'action' => 'show',
        'id' => NULL
));

Route::set('tags', '(<lang>/)tags(/<type>)/<tag_name>', array(
	'lang'      => $langs,
	'type'      => '\w+',
	'tag_name' => '[-_a-zа-я]+',
))
	->defaults(array(
		'directory' => 'blog',
		'controller' => 'tags',
		'action' => 'list',
));