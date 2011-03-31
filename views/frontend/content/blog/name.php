<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php echo HTML::anchor(
	Route::get('blog')->uri(array(
		'action' => 'show',
		'type' => $blog_type->name,
	)),
	$blog_type->description
)?>