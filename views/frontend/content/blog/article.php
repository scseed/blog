<?php defined('SYSPATH') or die('No direct access allowed.');?>
<h1><?php echo $article->title?></h1>
<?php echo str_replace('<p>~~~</p>', '<a name="article_cut"></a>', $textile->TextileThis($article->text))?>
<div id="tags">
	<?php echo Request::factory(Route::get('blog_tag')->uri(array(
		'action' => 'tree',
		'id' => $article->id
	)))->execute()->body()?>
</div>
<div id="stats">
	<?php echo Request::factory(Route::get('blog_stats')->uri(array(
		'action' => 'show',
		'id' => $article->id
	)))->execute()->body()?>
</div>
<div id="comments">
	<?php echo Request::factory(Route::get('blog_comment')->uri(array(
		'action' => 'tree',
		'id' => $article->id
	)))->execute()->body()?>
</div>