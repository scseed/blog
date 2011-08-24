<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php if ($images->count()) { ?>
<h4>Фотографии</h4>
<?php } else { ?>
<h4>Фотографии не загружены</h4>
<?php } ?>
<div class="frames" id="photos">
<?php
    foreach($images as $image):
?>
	<div class="frame" id="frame-<?php echo $car->id."-".$image->id;?>">
		<?php
                
            /*$last_dot = strrpos($image->url, '.');
            $thumb = substr($image->url, 0, $last_dot) . 'thumb' . substr($image->url, $last_dot);
            */
            echo HTML::anchor($image->url,
                HTML::image(
                    Utils::get_thumb($image->url),
                    array('title' => $image->title,
                         'alt' => $image->title,
                         'width' => 100,
                         'height' => 73,
                         'id' => 'image-'.$image->id)),
                array('rel' => 'fancybox'));
        if ($_user['member_id']==$car->user->id OR $_user['member_group_id']==$admin_group OR is_null($car)) {
            echo HTML::anchor(Route::get('blog_images')->uri(array(
                    'action' => 'del',
				    'id' => $image->id
				)), HTML::image('i/icons/delete.gif', array('title' => 'Удалить', 'alt' => 'Удалить')),
                              array('class' => 'delete-image', 'id' => 'delete-image-'.$image->id));
            
            if ($car->avatar->id != $image->id)
                echo HTML::anchor(Route::get('blog_images')->uri(array(
                        'action' => 'avatar',
                    )).'?image='.$image->id.'&car='.$car->id,
                                  HTML::image('i/icons/check.gif',
                                              array('title' => 'Сделать главной', 'alt' => 'Сделать главной')),
                          array('class' => 'avatar-image avatar', 'id' => 'avatar-image-'.$image->id.'-'.$car->id));
            else
                echo '<div class="avatar-main">'
                     .HTML::image('i/icons/check-ok.gif', array('title' => 'Главная', 'alt' => 'Главная'))
                     .'</div>';
        }
        ?>
	</div>
<?php endforeach;?>
</div>
<div class="clear"></div>
<?php
    if (is_null($car))
        $car_id = NULL;
    else
        $car_id = $car->id;
    if (is_null($car) OR $_user['member_id']==$car->user->id OR $_user['member_group_id']==$admin_group) {
        echo HTML::anchor(Route::get('blog_images')->uri(array(
                    'action' => 'new',
                    'id' => $car_id
                )), HTML::image('i/icons/add.gif',
                                           array('alt'=>'Добавить изображение',
                                           'title'=>'Добавить изображение')),
            array('id' => 'new-image'));
?>
    <div class="hide" id="new-image-block">
    <?php echo Request::factory(Route::url('blog_images', array('action'=>'new', 'id'=>$car_id)))->execute()->body();?>
    </div>
<?php
    }
?>
<div class="clear"></div>
