<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php
    if ($images->count()) {
?>
<div class="frames" id="photos">
    <h4>Фотографии</h4>
<?php
    foreach($images as $image):
	$article_url = Route::get('blog_article')->uri(array(
					'id' => $image->blog->id
				));
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
                array('rel' => 'fancybox'));
        if ( ! empty ($_user))
        if ($_user['member_id']==$article->author->id OR $_user['member_group_id']==$admin_group) {
            /*echo HTML::anchor(Route::get('blog_images')->uri(array(
                    'action' => 'edit',
				    'id' => $image->id
				)), HTML::image('i/icons/user.gif',
                array('title' => 'Подписать', 'alt' => 'Подписать')),
                              array ('id' => 'edit-image-'.$image->id, 'class' => 'edit-image'));*/
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
<?php } ?>
<div class="clear"></div>
<?php
    if ( ! empty ($_user))
    if ($_user['member_id']==$article->author->id OR $_user['member_group_id']==$admin_group) {
        echo HTML::anchor(Route::get('blog_images')->uri(array(
                    'action' => 'new',
				    'id' => $article->id
				)), HTML::image('i/icons/add.gif',
                                           array('alt'=>'Добавить изображение',
                                           'title'=>'Добавить изображение')),
            array('id' => 'new-image'));
?>
            <div class="hide" id="new-image-block">
            <?php echo Form::open(Request::current())?>
                <div class="form-item">
                    <?php echo Form::label('file', 'Картинка (*)')?>
                    <div id="file-error" class="error hide hint">Поле файл не может быть пустым</div>
                    <?php echo Form::file('file', array('id' => 'file', 'class' => 'needle'))?>
                </div>
                <div class="form-item">
                    <?php echo Form::label('title', 'Подпись')?>
                    <?php echo Form::input('title', NULL, array('id' => 'title'))?>
                </div>
                <div class="form-item">
                    <?php echo Form::button(NULL, 'Сохранить')?>
                </div>
                <?php echo Form::close();?>
            </div>
<?php
    }
?>
<div class="clear"></div>
