<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php $i=0; foreach($tags as $tag):?>
	<?php echo HTML::anchor(
		Route::get('blog_tag')->uri(array(
			'action' => 'show',
			'tag_name' => $tag->tag->name,
		)),
		$tag->tag->name,
		array('title' => $tag->tag->name)
	)?><?php if($tags_count != ++$i) echo ','?>
<?php endforeach;?>