<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div class="frames" id="photos-right-box">
<?php
    foreach($images as $image):
?>
	<div class="frame">
		<?php
            echo HTML::anchor($image->url,
                HTML::image(
				    $image->url,
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
