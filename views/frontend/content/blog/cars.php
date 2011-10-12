<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="posts" class="cars">
	<?php if ($my): ?>
		<div id="add_car">
			<?php echo HTML::anchor(Route::get('blog_cars')->uri(array(
                        'action' => 'new'
                    )), 'Новый автомобиль',
					array('class' => 'button_link'));
			?>
		</div>
	<?php endif ?>
<?php
    foreach($cars as $car):
?>
	<div class="post">
		<div class="info">
			<div class="avatar">
				<?php echo (!empty($car->avatar->url)) ?
					HTML::image(
						'media/cars/'.$car->id.'/'.$car->avatar->url.'.thumb.'.$car->avatar->ext,
						array('alt' => $car->model->name)
					) :
					HTML::image(
						'i/cars/placeholder.jpg',
						array('alt' => $car->model->name)
					)
				?>
			</div>
			<div class="additional">
				<?php  if ($my) { ?>
					<div class="actions">
						<?php
						echo HTML::anchor(Route::get('blog_cars')->uri(array(
									'action' => 'edit',
									'id' => $car->id
								)), HTML::image(
										'i/icons/edit.gif',
										array('alt' => 'Редактировать', 'class' => 'ico')
								),  array('class' => 'icon_link'));
						echo HTML::anchor(Route::get('blog_cars')->uri(array(
									'action' => 'edit',
									'id' => $car->id
								)), 'Редактировать',
								array('class' => 'text_link'));

						echo HTML::anchor(Route::get('blog_cars')->uri(array(
									'action' => 'del',
									'id' => $car->id
								)), HTML::image(
										'i/icons/delete.gif',
										array('alt' => 'Удалить', 'class' => 'ico')
								),  array('class' => 'icon_link'));
						echo HTML::anchor(Route::get('blog_cars')->uri(array(
									'action' => 'del',
									'id' => $car->id
								)), 'Удалить',
								array('class' => 'text_link last'));
						?>
					</div>
				<?php } ?>

				<h3><?php echo $car->model->name.' '.$car->year ?></h3>
				<?php echo HTML::anchor(
					Route::get('blog_cars')->uri(array(
						'action' => 'journal',
						 'id' => $car->id
					)),
					'Борт-журнал &rarr;'
					)
				?><br/>
				<?php echo HTML::anchor(
					Route::get('blog_cars')->uri(array(
						'action' => 'gallery',
						 'id' => $car->id
					)),
					'Галерея  &rarr;'
					)
				?>
			</div>
			<div class="clear"></div>
		</div>
        <p>
            <?php echo strip_tags($car->description) ?>
        </p>
	</div>
<?php endforeach;?>
</div>
<?php echo $pager; ?>
