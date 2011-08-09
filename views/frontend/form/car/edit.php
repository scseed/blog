<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="new_car">
	<?php echo Form::open(Request::current(), array('enctype'=>'multipart/form-data'))?>
	<div class="form-item">
		<?php echo Form::label('car_model', 'Модель')?>
		<?php echo Form::select('car[model]', $models->as_array('id', 'name'), $current_model, array('id' => 'car_model'))?>
	</div>
	<div class="form-item">
		<?php echo Form::label('car_year', 'Год (*)')?>
        <div id="car_year-error" class="error hide hint">Поле год не может быть пустым</div>
		<?php echo Form::input('car[year]', $post['car']['year'], array('id' => 'car_year', 'class' => 'needle'))?>
	</div>
	<div class="form-item">
		<?php echo Form::label('car_description', 'Текст')?>
		<?php echo Form::textarea('car[description]', $post['car']['description'], array('id' => 'car_description'))?>
	</div>
	<div class="form-item">
		<?php echo Form::button(NULL, $action)?>
	</div>
	<?php echo Form::close();?>
    <div class="hint">
        Поля, помеченные (*) обязательны для заполнения
    </div>
</div>
