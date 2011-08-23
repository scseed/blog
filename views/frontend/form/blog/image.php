<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div class="error"><?php echo $error; ?></div>
<?php echo Form::open(Route::get('blog_images')->uri(array(
        'action' => 'new',
        'id' => $car
    )), array('enctype'=>'multipart/form-data', 'id'=>'form-image-loader'))?>
    <div class="form-item">
        <div class="left"><h2>Загрузка изображения</h2></div><div class="right simplemodal-close">&nbsp;</div>
    </div>
    <div class="form-item">
        <?php echo Form::label('file', 'Картинка (*)')?>
        <div id="file-error" class="error hide hint">Поле файл не может быть пустым</div>
        <?php echo Form::file('file', array('id' => 'file', 'class' => 'needle'))?>
    </div>
    <div class="form-item">
        <?php echo Form::label('title', 'Подпись')?>
        <?php echo Form::input('title', NULL, array('id' => 'title'))?>
    </div>
    <div class="form-item">
        <?php echo Form::label('avatar', 'Сделать главной')?>
        <?php echo Form::checkbox('avatar', '1', FALSE, array('id' => 'avatar'))?>
    </div>
    <div class="form-item">
        <?php echo Form::hidden('x1')?>
        <?php echo Form::hidden('y1')?>
        <?php echo Form::hidden('w', 100)?>
        <?php echo Form::hidden('h', 73)?>
        <?php echo Form::hidden('car', $car)?>
        <?php echo Form::hidden('step', 1, array('id'=>'step'))?>
        <?php echo Form::button(NULL, 'Загрузить', array('id'=>'new-image-button'))?>
    </div>
    <div id="img-container" style="width: 300px; height: 300px"></div>
<?php echo Form::close();?>
