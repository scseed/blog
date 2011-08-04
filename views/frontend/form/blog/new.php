<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="new_article">
	<?php echo Form::open(Request::current(), array('enctype'=>'multipart/form-data'))?>
	<div class="form-item">
		<?php echo Form::label('blog_category', 'Категория')?>
		<?php echo Form::select('article[category]', $categories->as_array('id', 'title'), $current_category, array('id' => 'blog_category'))?>
	</div>
	<div class="form-item">
		<?php echo Form::label('blog_title', 'Заголовок (*)')?>
        <div id="blog_title-error" class="error hide hint">Поле заголовок не может быть пустым</div>
		<?php echo Form::input('article[title]', $post['article']['title'], array('id' => 'blog_title', 'class' => 'needle'))?>
	</div>
	<div class="form-item">
		<?php echo Form::label('blog_text', 'Текст (*)')?>
        <div id="blog_text-error" class="error hide hint">Поле текст не может быть пустым</div>
		<?php echo Form::textarea('article[text]', $post['article']['text'], array('id' => 'blog_text', 'class' => 'needle'))?>
	</div>
    <!--<div class="form-item">
		<?php //echo Form::label('blog_images', 'Изображения')?>
		<?php //echo Form::file('images[]', array('id' => 'blog_images', 'class'=>'multi', 'accept'=>'gif|jpg|png'))?>
	</div>-->
    <div class="form-item">
		<?php echo Form::label('blog_tags', 'Тэги')?>
		<?php echo Form::input('tags', $post['tags'], array('id' => 'blog_tags'))?>
	</div>
	<div class="form-item">
		<?php echo Form::button(NULL, 'Создать')?>
	</div>
	<?php echo Form::close();?>
    <div class="hint">
        Поля, помеченные (*) обязательны для заполнения
    </div>
    <div class="form-item">
        <?php
            /*echo Request::factory(Route::get('blog_images')->uri(array(
                    'action' => 'show',
                    'id' => NULL
                )))->execute()->body();*/
        ?>
    </div>
</div>
