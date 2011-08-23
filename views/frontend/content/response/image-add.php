<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<?php
if ($success) {
    if ($step==2) { echo "STEP2:";
?>
<div class="frame" id="frame-<?php echo "$car_id-$image_id";?>">
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
        if ($avatar !== 1)
            echo HTML::anchor(Route::get('blog_images')->uri(array(
                    'action' => 'avatar',
                )).'?image='.$image_id.'&car='.$car_id,
                              HTML::image('i/icons/user.gif',
                                          array('title' => 'Сделать главной', 'alt' => 'Сделать главной')),
                      array('class' => 'avatar-image avatar', 'id' => 'avatar-image-'.$image_id.'-'.$car_id));
        else
            echo '<div class="avatar-main">'
                 .HTML::image('i/icons/add.gif', array('title' => 'Главная', 'alt' => 'Главная'))
                 .'</div>';
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