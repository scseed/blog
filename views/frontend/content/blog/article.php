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
<?php
    if (! empty($_user)) {
        $score = Jelly::query('score')
                ->where('blog', '=', $article->id)
                ->and_where('user', '=', $_user['member_id'])
                ->count();
        if ($score == 0) {
?>
<!--<div class="right" id="like">
    <a title="" href="#">Мне нравится!</a><img class="ico right" alt="" src="/i/icons/add.gif">
</div>-->
<?php
        }
    }
?>
<div id="comments"><?php echo $comments?></div>
<div id="actions">
<?php
    if (! empty($_user)) {
        if ($_user['member_group_id']==$admin_group or $_user['member_id']==$article->author->id) {
            echo HTML::anchor(Route::get('blog_article')->uri(array(
					'id' => $article->id,
                    'action' => 'edit'
				)), 'Правка');
            echo HTML::anchor(Route::get('blog_article')->uri(array(
					'id' => $article->id,
                    'action' => 'del'
				)), 'Удалить', array('class'=>'button-confirm'));
        }

        // автор может оставить заявку на замену, но если он сам админ, то смена произойдет напрямую
        $demand = Jelly::query('blog_demand')->where('blog', '=', $article->id)
                        ->and_where('is_done', '=', 0)->limit(1)->select();
        if ($demand->loaded())
        {
            echo 'Статья находится на модерации';
        }
        else
        {
            if ($_user['member_id']==$article->author->id) {
                echo HTML::anchor(Route::get('blog_article')->uri(array(
                    'id' => $article->id,
                    'action' => 'move'
                    )), ($_user['member_group_id']==$admin_group)? 'Сменить категорию': 'Заявка на смену категории');
            }
        }
    }
?>
</div>