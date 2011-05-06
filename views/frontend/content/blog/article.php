<?php defined('SYSPATH') or die('No direct access allowed.');?>
<h1><?php echo $article->title?></h1>
<?php echo str_replace('~~~', '<a name="article_cut"></a>', $textile->TextileThis($article->text))?>
<div id="tags">
	<span>Тэги:</span>
	<?php echo Request::factory(Route::get('blog_tag')->uri(array(
		'action' => 'tree',
		'id' => $article->id
	)))->execute()->body()?>
</div>
<div id="stats">
	<span>Информация:</span>
	<?php echo Request::factory(Route::get('blog_stats')->uri(array(
		'action' => 'show',
		'id' => $article->id
	)))->execute()->body()?>
</div>
<div id="comments">
	<?php echo $comments?>
</div>