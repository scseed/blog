<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="tags">
	<?php $i=0; foreach($tags as $tag):?>
		<?php echo HTML::anchor(
			Route::get('blog')->uri(array(
				'action' => 'tag',
				'type' => $tag->name,
			)),
			$tag->name,
			array('title' => $tag->name)
		)?><?php if($tags_count != ++$i) echo ','?>
	<?php endforeach;?>
</div>