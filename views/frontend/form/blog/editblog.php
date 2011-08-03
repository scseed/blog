<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div class="error"><?php echo $error; ?></div>
<div id="action_category">
	<?php echo Form::open(Request::current())?>
    <div class="form-item">
        <?php echo Form::label('cat', 'Категория')?>
        <?php
        if ($_user['member_group_id']!=$admin_group)
            echo Form::select('post[cat]', $cat, NULL, array('id' => 'cat', 'disabled' => 'disabled'));
        else
            echo Form::select('post[cat]', $cat, NULL, array('id' => 'cat'));
        ?>
    </div>
    <div class="form-item">
        <?php echo Form::label('name', 'URL (*)')?>
        <div id="name-error" class="error hide hint">Поле URL не может быть пустым</div>
        <?php
        if ($_user['member_group_id']!=$admin_group)
            echo Form::input('post[name]', 'car_book', array('id' => 'name', 'readonly'=>'readonly', 'class' => 'needle'));
        else
            echo Form::input('post[name]', $post['post']['name'], array('id' => 'name', 'class' => 'needle'))
        ?>
    </div>
	<div class="form-item">
		<?php echo Form::label('title', 'Заголовок (*)')?>
        <div id="title-error" class="error hide hint">Поле заголовок не может быть пустым</div>
		<?php echo Form::input('post[title]', $post['post']['title'], array('id' => 'title', 'class' => 'needle'))?>
	</div>
	<div class="form-item">
		<?php echo Form::label('description', 'Описание')?>
		<?php echo Form::textarea('post[description]', $post['post']['description'], array('id' => 'description'))?>
	</div>
	<div class="form-item">
		<?php echo Form::button(NULL, 'Сохранить')?>
	</div>
	<?php echo Form::close();?>
    <div class="hint">
        Поля, помеченные (*) обязательны для заполнения
    </div>
</div>
