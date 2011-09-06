<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php echo Request::factory(Route::get('blog_filter')->uri(array(
    'action' => 'show',
)))->execute()->body()?>
<div id="posts">
<?php
foreach($articles as $blog_article):
	$article_url = Route::get('blog')->uri(array(
					'category' => $blog_article->category->name,
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
			<?php //echo $textile->TextileThis($blog_article->intro())?>
            <?php echo $blog_article->intro()?>
			<p><?php echo HTML::anchor(
				$article_url . '#article_cut',
				'Читать дальше &rarr;',
				array('title' => $blog_article->title)
			)?></p>
		</div>
<?php
        echo View::factory('frontend/content/blog/badges')
                ->set('article', $blog_article)
                ->set('user_avatar', $user_avatar)
                ->set('article_url', $article_url);
?>
	</div>
<?php endforeach;?>
</div>
<?php echo $pager; ?>
<!--<a href="#" class="right more">Перейти в блоги</a>-->