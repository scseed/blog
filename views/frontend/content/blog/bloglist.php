<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div>
<?php
    if ($_user['member_group_id']==$admin_group)
        echo HTML::anchor(
	        Route::url('blog_action', array('action' => 'new', 'lang' => I18n::lang())),
	        __('Создать новую категорию'),
	        array('class' => 'button')
        )?>
</div>
<br /><br />

<div id="posts">
<?php
foreach($categories as $category):
?>
	<div class="post">
		<h2><?php if ($category->name == 'car_book')
                echo HTML::anchor(Route::url('article_list', array('category'=>$category->name, 'id'=>$category->id, 'lang' => I18n::lang())), $category->title);
            else
                echo HTML::anchor(Route::url('blog', array('category'=>$category->name, 'lang' => I18n::lang())), $category->title);?></h2>
        <p><?php echo HTML::chars($category->description);?></p>
        <!--<p><strong>Владелец: </strong><?php //echo $category->user->name; ?></p>-->
<?php if ($_user['member_group_id']==$admin_group) { ?>
		<div id="actions">
			<?php
                echo HTML::anchor(
                    Route::url('blog_action', array('action' => 'edit', 'id'=>$category->id, 'lang' => I18n::lang())),
                    __('Редактировать'),
                    array('class' => 'button')
                );
            echo HTML::anchor(
                Route::url('blog_action', array('action' => 'del', 'id'=>$category->id, 'lang' => I18n::lang())),
                __('Удалить'),
                array('class' => 'button button-confirm')
            );
            ?>
		</div>
<?php } ?>
	</div>
<?php endforeach;?>
</div>
