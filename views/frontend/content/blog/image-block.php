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
                array('rel' => 'fancybox-right', 'title' =>  $image->title));

        ?>
        <div style="display: none;">
        <?php
            $im = Image::factory($path . '/' . $image->car->id . '/' . $image->url . '.' . $image->ext);
            $style = '';
            if ($im->height<800)
                $style .= 'height: '.$im->height . ';';
            if ($im->width<1000)
                $style .= 'width: '.$im->width . ';';
            $img_atr = array();
            if ($im->height>=800 or $im->width>=1000)
                $img_atr = array('width'=>'100%', 'height'=>'100%');

        ?>
            <div id="<?php echo $image->url?>" style="<?php echo $style;?>">
            <?php
                echo HTML::image($path . '/' . $image->car->id . '/' . $image->url . '.' . $image->ext, $img_atr); ?>
                <div style="width: 100%; position: absolute; top: 0px; text-align: center;">
                <?php echo HTML::anchor(Route::get('blog_cars')->uri(array('action'=>'gallery', 'id'=>$image->car->id)),
                            'Перейти в галерею'); ?>
                </div>
            </div>
        </div>
	</div>
<?php } } ?>
<div class="clear"></div>
</div>
</div>
<!--<a href="#" title="" class="more right">Все фотографии</a>-->
