<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<?php
if ($success) {
    if ($step==2) { echo "STEP2:";
?>
<div class="frame">
    <?php
        echo HTML::anchor($url,
            HTML::image(
                $thumb,
                array('title' => $title,
                     'alt' => $title,
                     'width' => 100,
                     'height' => 73,
                     'id' => 'image-'.$image_id)),
            array('rel' => 'fancybox'));
        echo HTML::anchor(Route::get('blog_images')->uri(array(
                'action' => 'del',
                'id' => $image_id
            )), HTML::image('i/icons/user.gif', array('title' => 'Удалить', 'alt' => 'Удалить')),
                          array('class' => 'delete-image', 'id' => 'delete-image-'.$image_id));
    ?>
</div>
<?php
    } else {
        echo "STEP1:";
        echo "<h3>Создание аватарки</h3>";
        echo HTML::image($filename, array('width'=>'300'));
        echo Form::hidden('filename', $filename);
    }
} else {
    echo "ERROR:".$error;
}