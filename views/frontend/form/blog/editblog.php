<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div class="error"><?php echo $error; ?></div>
<div id="new_article">
	<?php echo Form::open(Request::current())?>
    <div class="form-item">
        <?php echo Form::label('name', 'URL')?>
        <?php
        if ($_user['member_group_id']!=$admin_group)
            echo Form::input('post[name]', 'car_book', array('id' => 'name', 'readonly'=>'readonly'));
        else
            echo Form::input('post[name]', $post['post']['name'], array('id' => 'name'))
        ?>
    </div>
	<div class="form-item">
		<?php echo Form::label('title', 'Заголовок')?>
		<?php echo Form::input('post[title]', $post['post']['title'], array('id' => 'title'))?>
	</div>
	<div class="form-item">
		<?php echo Form::label('description', 'Описание')?>
		<?php echo Form::textarea('post[description]', $post['post']['description'], array('id' => 'description'))?>
	</div>
	<div class="form-item">
		<?php echo Form::button(NULL, 'Сохранить')?>
	</div>
	<?php echo Form::close();?>
</div>
