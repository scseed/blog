<?php defined('SYSPATH') or die('No direct access allowed.');?>
<h5>Фотографии</h5>
<div id="photos-wrapper">
<div class="frames" id="photos-right-box">
<?php
    $i = 0;
    foreach($images as $image) {
        if (file_exists(DOCROOT . $path . '/' . $image->car->id . '/' . $image->url . '.' . $image->ext)) {
            $i++;
            if ($i>6) break;
?>
	<div class="frame">
		<?php

            echo HTML::anchor($path . '/' . $image->car->id . '/' . $image->url . '.' . $image->ext,
                HTML::image(
                    $path . '/' . $image->car->id . '/' . $image->url . '.thumb.' . $image->ext,
                    array('title' => $image->title,
                         'alt' => $image->title,
                         'width' => 100,
                         'height' => 73,
                         'id' => 'image-'.$image->id)),
                array('rel' => 'fancybox-right', 'title' =>  $image->title, 'id' => $image->url));

        ?>
            <div class="<?php echo $image->url?>" style="display: none;">
	            <span class="fancybox-title-float-wrap">
		            <span class="fancybox-title-float-left"></span>
		            <span class="fancybox-title-float-main">
			            <?php echo HTML::anchor(Route::get('blog_cars')->uri(array('action'=>'gallery', 'id'=>$image->car->id)),
                            'Перейти в галерею'); ?>
		            </span>
		            <span class="fancybox-title-float-right"></span>
	            </span>
            </div>
	</div>
<?php } } ?>
<div class="clear"></div>
</div>
</div>
<!--<a href="#" title="" class="more right">Все фотографии</a>-->
