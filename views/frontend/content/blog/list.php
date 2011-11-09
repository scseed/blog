<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php echo Request::factory(Route::url('blog_filter', array(
	'lang' => I18n::lang(),
    'action' => 'show',
)))->execute()->body()?>
<div id="posts">
    <div class="post">
    <?php echo HTML::anchor(
        Route::url('blog_article', array('lang' => I18n::lang(), 'action' => 'new', 'category' => $category)),
        __('Написать статью в блог'),
        array('class' => 'button')
    )?>
    </div>
<?php
    if (! empty($articles))
    foreach($articles as $blog_article):
    $article_url = Route::url('blog_article', array(
        'lang' => I18n::lang(),
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