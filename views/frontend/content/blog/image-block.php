<?php defined('SYSPATH') or die('No direct access allowed.');?>
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
                array('rel' => 'fancybox-right', 'title' =>  $image->title . '&nbsp;' .
                        HTML::anchor(Route::get('blog_cars')->uri(array('action'=>'gallery', 'id'=>$image->car->id)),
                            'Перейти в галерею')));

        ?>
	</div>
<?php } } ?>
<div class="clear"></div>
</div>
