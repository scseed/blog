<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div class="badges">
    <div class="left author">
        <div class="badge">
            <?php echo
                HTML::anchor(
                    //Route::url('forum', array('app' => 'user', 'module' => $article->author->id . '-' . $article->author->name)),
                    Route::url('user_data', array('id' => $article->author->id)),
                    HTML::image($user_avatar, array('alt' => 'Автор')),
                    array('title' => 'Автор статьи')
                );
            ?>
            <div class="title">
                <?php echo HTML::anchor(
                    //Route::url('forum', array('app' => 'user', 'module' => $article->author->id . '-' . $article->author->name)),
                    Route::url('user_data', array('id' => $article->author->id)),
                    $article->author->name,
                    array('title' => $article->author->name)
                );
                ?>
            </div>
        </div>
    </div>
    <div class="right">
        <?php echo Request::factory(Route::get('likes')->uri(array(
            'action' => empty($like_action)? 'count': $like_action,
            'type' => 'blog',
            'object' => $article->id
        )))->execute()->body()?>
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
                            ->where('object_id', '=', $article->id)
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
                <?php echo date('d.m.Y', $article->date_create)?>
            </span></div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
