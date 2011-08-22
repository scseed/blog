<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div class="frames" id="photos-right-box">
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
                array('rel' => 'fancybox-right'));
        ?>
	</div>
<?php endforeach;?>
<div class="clear"></div>
</div>
