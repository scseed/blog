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
	<div class="frame">
		<?php
                
            $last_dot = strrpos($image->url, '.');
            $thumb = substr($image->url, 0, $last_dot) . 'thumb' . substr($image->url, $last_dot);
            
            echo HTML::anchor($image->url,
                HTML::image(
				    $thumb,
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
				)), HTML::image('i/icons/user.gif', array('title' => 'Удалить', 'alt' => 'Удалить')),
                              array('class' => 'delete-image', 'id' => 'delete-image-'.$image->id));
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
