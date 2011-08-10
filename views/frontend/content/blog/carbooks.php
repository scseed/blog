<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="posts">
    <div class="post">
    <?php echo HTML::anchor(
        Route::url('blog_article', array('action' => 'new', 'category' => 'self')),
        __('Написать статью в блог'),
        array('class' => 'button')
    )?>
    </div>
<?php
foreach($carbooks as $blog_article):
	$article_url = Route::get('blog_article')->uri(array(
					'id' => $blog_article->id
				));
	$user_avatar = ($blog_article->author->has_avatar)
		? 'media/images/avatars/'.$blog_article->author->id.'/thumb.jpg'
		: 'i/icons/user.gif';
?>
	<div class="post">
		<h2><?php echo HTML::anchor(
				$article_url,
				$blog_article->title,
				array('title' => $blog_article->title))?>
		</h2>
		<div class="text">
			<?php echo $textile->TextileThis($blog_article->intro())?>
			<p><?php echo HTML::anchor(
				$article_url . '#article_cut',
				'Читать дальше &rarr;',
				array('title' => $blog_article->title)
			)?></p>
		</div>

		<div class="badges">
			<div class="right">
				<div class="badge left">
					<?php echo HTML::image('i/icons/favorites.gif', array('alt' => ''))?>
					<div class="title"><span>
						<?php echo $blog_article->score?>
					</span></div>
				</div>
				<div class="badge left">
					<?php echo HTML::anchor(
							$article_url. '#comments',
							HTML::image('i/icons/views.gif', array('alt' => 'Комментарии')),
							array('title' => 'Комментарии')
						);
					?>
					<div class="title">
						<?php echo HTML::anchor(
							$article_url . '#comments',
							Jelly::query('comment')
								->where('type', '=', 'blog')
								->where('object_id', '=', $blog_article->id)
								->count(),
							array('title' => 'Комментарии')
						)?>
					</div>
				</div>
				<div class="badge left">
					<?php echo HTML::image('i/icons/go.gif', array('alt' => 'Дата создания'))?>
					<div class="title" title="Дата создания"><span>
						<?php echo date('d.m.Y', $blog_article->date_create)?>
					</span></div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
<?php endforeach;?>
</div>
<?php echo $pager; ?>
<!--<a href="#" class="right more">Перейти в блоги</a>-->