<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div class="frames" id="photos-right-box">
<?php
    $i = 0;
    foreach($images as $image) {
        if (file_exists(DOCROOT . $image->url)) {
            $i++;
            if ($i>6) break;
?>
	<div class="frame">
		<?php

            echo HTML::anchor($image->url,
                HTML::image(
                    Utils::get_thumb($image->url),
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
