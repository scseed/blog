<?php defined('SYSPATH') or die('No direct access allowed.');?>
<h1><?php echo $article->title?></h1>
<?php echo str_replace('<p>~~~</p>', '<a name="article_cut"></a>', $textile->TextileThis($article->text))?>
<div id="stats">
	<?php echo Request::factory(Route::get('blog')->uri(array(
		'action' => 'stats',
		'id' => $article->id
	)))->execute()->body()?>
</div>
<div id="comments">
	<?php echo Request::factory(Route::get('blog')->uri(array(
		'action' => 'comments',
		'id' => $article->id
	)))->execute()->body()?>
</div>