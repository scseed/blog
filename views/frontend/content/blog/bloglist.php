<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div>
<?php echo HTML::anchor(
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
		<h2><?php echo $category->name;?></h2>
        <p><strong>Заголовок: </strong><?php
            if ($category->name == 'car_book')
                echo HTML::anchor(Route::url('article_list', array('category'=>$category->name, 'id'=>$category->id, 'lang' => I18n::lang())), $category->title);
            else
                echo HTML::anchor(Route::url('blog', array('category'=>$category->name, 'lang' => I18n::lang())), $category->title);
        ?></p>
        <p><strong>Описание: </strong><?php echo HTML::chars($category->description);?></p>
        <p><strong>Владелец: </strong><?php echo $category->user->name; ?></p>
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
                array('class' => 'button')
            );
            ?>
		</div>
	</div>
<?php endforeach;?>
</div>
