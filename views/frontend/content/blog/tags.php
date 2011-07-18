<?php defined('SYSPATH') or die('No direct access allowed.');?>
<h3><?php echo __('Tags')?>:</h3>
<div id="tags">
	<?php $i=0; foreach($objects_tags as $object_tag):?>
		<?php echo HTML::anchor(
			Route::url('tags', array(
				'lang' => I18n::lang(),
				'action' => 'show',
				'object_id' => $object_tag->tag->name,
			)),
			$object_tag->tag->name,
			array('title' => $object_tag->tag->name)
		)?><?php echo (++$i < $tags_count) ? ', ' : NULL;?>
	<?php endforeach;?>
</div>