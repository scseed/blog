<?php defined('SYSPATH') or die('No direct access allowed.');?>
<h4>Новая категория</h4>
<div id="new_article">
	<?php echo Form::open(Request::current())?>
	<div class="form-item">
		<?php echo Form::label('cat', 'Категория')?>
		<?php
        if ($this->_user['member_group_id']!=$admin_group)
            echo Form::select('post[cat]', $cat, NULL, array('id' => 'cat', 'disabled' => 'disabled'));
        else
            echo Form::select('post[cat]', $cat, NULL, array('id' => 'cat'));
        ?>
	</div>
    <div class="form-item">
        <?php echo Form::label('name', 'URL')?>
        <?php
        if ($this->_user['member_group_id']!=$admin_group)
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
        <?php echo Form::hidden('post[user]', $post['post']['user'])?>
		<?php echo Form::button(NULL, 'Создать')?>
	</div>
	<?php echo Form::close();?>
</div>
