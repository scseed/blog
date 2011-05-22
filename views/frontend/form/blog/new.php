<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php echo Form::open(Request::current())?>
<div class="form-item">
	<?php echo Form::label('blog_type', 'Категория')?>
	<?php echo Form::select('type', $types->as_array('id', 'description'), $post['type'], array('id' => 'blog_type'))?>
</div>
<div class="form-item">
	<?php echo Form::label('blog_title', 'Заголовок')?>
	<?php echo Form::input('title', $post['title'], array('id' => 'blog_title'))?>
</div>
<div class="form-item">
	<?php echo Form::label('blog_text', 'Текст')?>
	<?php echo Form::textarea('text', $post['text'], array('id' => 'blog_text'))?>
</div>
<div class="form-item">
	<?php echo Form::label('blog_tags', 'Тэги')?>
	<?php echo Form::input('tags', $post['tags'], array('id' => 'blog_tags'))?>
</div>
<div class="form-item">
	<?php echo Form::button(NULL, 'Создать')?>
</div>
<?php echo Form::close();?>