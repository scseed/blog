<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php //echo str_replace('~~~', '<a name="article_cut"></a>', $textile->TextileThis($article->text))?>
<?php echo str_replace('~~~', '<a name="article_cut"></a>', $article->text)?>
<!--<div id="stats">
	<span>Информация:</span>
	<?php /*echo Request::factory(Route::get('blog_stats')->uri(array(
		'action' => 'show',
		'id' => $article->id
	)))->execute()->body()*/?>
</div>-->
<div id="tags"><?php echo $tags?></div>
<?php /*echo Request::factory(Route::get('likes')->uri(array(
    'action' => 'show',
    'type' => 'blog',
    'object' => $article->id
)))->execute()->body()*/
$user_avatar = ($article->author->has_avatar)
    ? 'media/images/avatars/'.$article->author->id.'/thumb.jpg'
    : 'i/icons/user.gif';

echo View::factory('frontend/content/blog/badges')
        ->set('article', $article)
        ->set('user_avatar', $user_avatar)
        ->set('article_url', $article_url);
?>
<div id="comments"><?php echo $comments?></div>
<div id="actions">
<?php
    if (! empty($_user)) {
        if ($_user['member_group_id']==$admin_group or $_user['member_id']==$article->author->id) {
            echo HTML::anchor(Route::get('blog_article')->uri(array(
                    'action' => 'new'
				)), 'Новая статья');
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