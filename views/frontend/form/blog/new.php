<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="new_article">
	<?php echo Form::open(Request::current())?>
	<div class="form-item">
		<?php echo Form::label('blog_category', 'Категория')?>
		<?php echo Form::select('article[category]', $categories->as_array('id', 'title'), $current_category, array('id' => 'blog_category'))?>
	</div>
	<div class="form-item">
		<?php echo Form::label('blog_title', 'Заголовок')?>
		<?php echo Form::input('article[title]', $post['article']['title'], array('id' => 'blog_title'))?>
	</div>
	<div class="form-item">
		<?php echo Form::label('blog_text', 'Текст')?>
		<?php echo Form::textarea('article[text]', $post['article']['text'], array('id' => 'blog_text'))?>
	</div>
	<div class="form-item">
		<?php echo Form::label('blog_tags', 'Тэги')?>
		<?php echo Form::input('tags', $post['tags'], array('id' => 'blog_tags'))?>
	</div>
	<div class="form-item">
		<?php echo Form::button(NULL, 'Создать')?>
	</div>
	<?php echo Form::close();?>
</div>
