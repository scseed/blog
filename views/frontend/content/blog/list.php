<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php echo Request::factory(Route::get('blog_filter')->uri(array(
    'action' => 'show',
)))->execute()->body()?>
<div id="posts">
    <div class="post">
    <?php echo HTML::anchor(
        Route::url('blog_article', array('action' => 'new', 'category' => 'self')),
        __('Написать статью в блог'),
        array('class' => 'button')
    )?>
    </div>
<?php
    if (! empty($articles))
    foreach($articles as $blog_article):
    $article_url = Route::get('blog_article')->uri(array(
					'id' => $blog_article->id
				));
    //echo $_ipbwi->member->avatar($blog_article->author->id);
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
			<?php //echo $textile->TextileThis($blog_article->intro())?>
            <?php echo $blog_article->intro()?>
			<p><?php echo HTML::anchor(
				$article_url . '#article_cut',
				'Читать дальше &rarr;',
				array('title' => $blog_article->title)
			)?></p>
		</div>

		<div class="badges">
			<div class="left author">
				<div class="badge">
					<?php echo
						HTML::anchor(Route::url('forum',
                                       array('app' => 'user',
                                            'module' => $blog_article->author->id . '-' . $blog_article->author->name)),
							HTML::image($user_avatar, array('alt' => 'Автор')),
							array('title' => 'Автор статьи')
						);
					?>
					<div class="title">
						<?php echo HTML::anchor(
                            Route::url('forum',
                                       array('app' => 'user',
                                            'module' => $blog_article->author->id . '-' . $blog_article->author->name)),
							$blog_article->author->name,
							array('title' => $blog_article->author->name)
						); 
						?>
					</div>
				</div>
			</div>
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
						<?php
                            $comments_root = Jelly::query('comment')->where(':type.name', '=', 'blog')
                                    ->where('object_id', '=', $blog_article->id)
                                    ->where('level', '=', NULL)->limit(1)->select();
                            $number_of_comments = $comments_root->right/2 - 1;
                        echo HTML::anchor(
							$article_url . '#comments',
							$number_of_comments,
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