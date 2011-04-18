<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php echo HTML::anchor(
	Route::url('blog_article', array('action' => 'new')),
	'Новая статья'
)?>
<br />
<?php echo HTML::anchor(
	Route::url('blog', array('action' => 'list', 'type' => 'my')),
	'Собственные статьи'
)?>