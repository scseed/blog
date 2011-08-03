<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div class="error"><?php echo $error; ?></div>
<iframe name="file-loader" id="file-loader" style="display: none;"></iframe>
<?php echo Form::open(Route::get('blog_images')->uri(array(
        'action' => 'new',
        'id' => $article
    )), array('enctype'=>'multipart/form-data', 'target'=>'file-loader'))?>
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
        <?php echo Form::button(NULL, 'Добавить', array('id'=>'new-image-button'))?>
    </div>
<?php echo Form::close();?>
