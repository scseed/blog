<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="new_car">
	<?php echo Form::open(Request::current(), array('enctype'=>'multipart/form-data'))?>
	<div class="form-item">
		<?php echo Form::label('car_model', 'Модель')?>
		<?php echo Form::select('car[model]', $models->as_array('id', 'name'), $current_model, array('id' => 'car_model'))?>
	</div>
	<div class="form-item">
		<?php echo Form::label('car_year', 'Год')?>
        <?php echo Form::select('car[year]', $years, $current_year, array('id' => 'car_year'))?>
	</div>
	<div class="form-item">
		<?php echo Form::label('car_description', 'Текст')?>
		<?php echo Form::textarea('car[description]', $post['car']['description'], array('id' => 'car_description'))?>
	</div>
	<div class="form-item">
		<?php echo Form::button(NULL, $action)?>
	</div>
	<?php echo Form::close();?>
    <div class="form-item">
        <?php
            echo Request::factory(Route::get('blog_images')->uri(array(
                    'action' => 'show',
                    'id' => $car
                )))->execute()->body();
        ?>
    </div>
</div>
