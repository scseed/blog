<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="posts">
<?php foreach($blog_articles as $blog_article):?>
	<div class="post">
		<h2>
			<?php echo HTML::anchor(
				Route::get('blog')->uri(array(
					'action' => 'show',
					'type' => $blog_article->type->name
				)),
				$blog_article->type->description,
				array('title' => $blog_article->type->description)
			)?>
			/
			<?php echo HTML::anchor(
				Route::get('blog_article')->uri(array(
					'action' => 'show',
					'id' => $blog_article->id
				)),
				$blog_article->title,
				array('title' => $blog_article->title))?>
		</h2>
		<div class="text">
			<?php echo $textile->TextileThis($blog_article->intro())?>
			<p><?php echo HTML::anchor(
				Route::get('blog_article')->uri(array(
					'action' => 'show',
					'id' => $blog_article->id
				)) . '#article_cut',
				'Читать дальше &rarr;',
				array('title' => $blog_article->title)
			)?></p>
		</div>

		<div class="badges">
			<div class="left author">
				<div class="badge">
					<?php echo
						HTML::anchor(
							Route::get('default')->uri(array(
								'controller' => 'user',
								'action' => 'profile',
								'id' => $blog_article->author->id,
							)),
							HTML::image('i/icons/user.gif', array('alt' => 'Автор')),
							array('title' => 'Автор статьи')
						);
					?>
					<div class="title">
						<?php echo HTML::anchor(
							Route::get('default')->uri(array(
								'controller' => 'user',
								'action' => 'profile',
								'id' => $blog_article->author->id,
							)),
							$blog_article->author->name,
							array('title' => $blog_article->author->name)
						)?>
					</div>
				</div>
			</div>
			<div class="right">
				<div class="badge left">
					<?php echo HTML::image('i/icons/favorites.gif', array('alt' => ''))?>
					<div class="title">
						<?php echo $blog_article->score?>
					</div>
				</div>
				<div class="badge left">
					<?php echo HTML::anchor(
							Route::get('blog_article')->uri(array(
								'controller' => 'article',
								'action' => 'show',
								'id' => $blog_article->id
							)) . '#comments',
							HTML::image('i/icons/views.gif', array('alt' => 'Комментарии')),
							array('title' => 'Комментарии')
						);
					?>
					<div class="title">
						<?php echo HTML::anchor(
							Route::get('blog_article')->uri(array(
								'controller' => 'article',
								'action' => 'show',
								'id' => $blog_article->id
							)) . '#comments',
							$blog_article->count_comments(),
							array('title' => 'Комментарии')
						)?>
					</div>
				</div>
				<div class="badge left">
					<?php echo HTML::image('i/icons/go.gif', array('alt' => 'Дата создания'))?>
					<div class="title" title="Дата создания">
						<?php echo date('d.m.Y', $blog_article->date_create)?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
<?php endforeach;?>
</div>
<!--<a href="#" class="right more">Перейти в блоги</a>-->