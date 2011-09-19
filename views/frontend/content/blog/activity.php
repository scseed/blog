<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="events">
    <div class="title left">Мероприятия</div>
    <ul class="paginator left">
        <li></li>
    </ul>
    <div class="clear"></div>
    <div id="events-rotator">
        <ul>
            <?php
                foreach ($articles as $article) {
                    echo '<li>';

                    $intro = HTML_Parser::factory($article->intro())->plaintext;
                    echo HTML::anchor(Route::get('blog_article')->uri(array('id'=>$article->id)),
                        $intro, array('class'=>'description'));
                    echo '</li>';
                }
            ?>
        </ul>
    </div>
</div>
