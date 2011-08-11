<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<?php if ($success) { ?>
<div class="frame">
    <?php
        echo HTML::anchor($url,
            HTML::image(
                $url,
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
<script type="text/javascript">
    $('a[rel=fancybox]').fancybox({
        overlayColor: '#000',
        showNavArrows: true
    });
</script>
<?php } else { ?>
<div class="error">
    <?php echo $error; ?>
</div>
<?php }  ?>