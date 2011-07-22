<?php defined('SYSPATH') or die('No direct access allowed.');?>
<h4><?php echo $title; ?></h4>
<div id="new_article">
	<?php echo Form::open(Request::current())?>
	<div class="form-item">
		<?php echo Form::label('blog_category', 'Категория')?>
		<?php echo Form::select('article[category]', $categories->as_array('id', 'title'), $current_category, array('id' => 'blog_category'))?>
	</div>
<?php
    if ($_user['member_group_id']!=$admin_group) {
?>
    <div class="form-item">
        <?php echo Form::label('blog_message', 'Сообщение')?>
        <?php echo Form::textarea('article[message]', '', array('id' => 'blog_message')); ?>
    </div>
<?php
    }
?>
	<div class="form-item">
		<?php echo Form::button(NULL, 'Сохранить')?>
	</div>
	<?php echo Form::close();?>
</div>
