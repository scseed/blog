<?php defined('SYSPATH') or die('No direct access allowed.');?>
<h1><?php echo $article->title?></h1>
<?php echo str_replace('~~~', '<a name="article_cut"></a>', $textile->TextileThis($article->text))?>
<div id="stats">
	<span>Информация:</span>
	<?php echo Request::factory(Route::get('blog_stats')->uri(array(
		'action' => 'show',
		'id' => $article->id
	)))->execute()->body()?>
</div>
<div id="tags"><?php echo $tags?></div>
<div id="comments"><?php echo $comments?></div>
<div id="actions">
<?php
    if (! empty($_user)) {
        @include ipbwi_BOARD_PATH.'conf_global.php';
        $admin_group = intval($INFO['admin_group']);
        if ($_user['member_group_id']==$admin_group) {
            echo HTML::anchor(Route::get('blog_article')->uri(array(
					'id' => $article->id,
                    'action' => 'edit'
				)), 'Правка');
            echo HTML::anchor(Route::get('blog_article')->uri(array(
					'id' => $article->id,
                    'action' => 'del'
				)), 'Удалить');

            echo HTML::anchor(Route::get('blog_article')->uri(array(
					'id' => $article->id,
                    'action' => 'move'
				)), 'Сменить категорию');

        }
        elseif ($_user['member_id']==$article->author->id) {
            echo HTML::anchor(Route::get('blog_article')->uri(array(
					'id' => $article->id,
                    'action' => 'edit'
				)), 'Правка');

            echo HTML::anchor(Route::get('blog_article')->uri(array(
					'id' => $article->id,
                    'action' => 'del'
				)), 'Удалить');
        }
    }
?>
</div>
