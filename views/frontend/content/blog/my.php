<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php echo HTML::anchor(
	Route::url('blog_article', array('action' => 'new')),
	'Новая статья',
	array('class' => 'button_link')
)?>
&nbsp;
<?php echo HTML::anchor(
	Route::url('blog', array('action' => 'list', 'type' => 'my')),
	'Собственные статьи',
	array('class' => 'button_link')
)?>