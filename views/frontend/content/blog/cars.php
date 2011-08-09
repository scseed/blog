<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="posts">
<?php
    if ($my) {
        echo HTML::anchor(Route::get('blog_cars')->uri(array(
					'action' => 'new'
				)), 'Новый автомобиль');
    }
    foreach($cars as $car):
?>
	<div class="post">
		<h3><?php echo $car->model->name.' '.$car->year ?></h3>
        <p>
            <?php echo $textile->TextileThis($car->description) ?>
        </p>
        <p><?php echo HTML::anchor(
                Route::get('blog_cars')->uri(array(
                    'action' => 'journal',
                     'id' => $car->id
                )),
                'Борт-журнал'
                )?>
        </p>
        <p><?php echo HTML::anchor(
				Route::get('blog_cars')->uri(array(
					'action' => 'gallery',
                     'id' => $car->id
				)),
				'Галерея'
				)?></p>
        <?php
            if ($my) {
                echo HTML::anchor(Route::get('blog_cars')->uri(array(
                            'action' => 'edit',
                            'id' => $car->id
                        )), 'Редактировать');
                echo HTML::anchor(Route::get('blog_cars')->uri(array(
                            'action' => 'del',
                            'id' => $car->id
                        )), 'Удалить', array('class' => 'button-confirm'));
            }
        ?>
	</div>
<?php endforeach;?>
</div>
<?php echo $pager; ?>
