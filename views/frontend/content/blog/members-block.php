<?php defined('SYSPATH') or die('No direct access allowed.');?>
<h5>Наши люди</h5>
<div id="peoples-wrapper">
    <div id="peoples" class="frames">
        <?php
            $ipbwi = Ipbwi::instance();
            $i = 0;
            $total = intval($members->count() / 3)*3;

            foreach ($members as $member) {
                $i++;
                if ($i>$total) break;
                ?>
                <div class="frame">
                    <?php
                        echo HTML::anchor(
                            Route::url('forum', array('app' => 'user',
                                                    'module' => $member['member_id'] . '-' . $member['name'])),
                            $ipbwi->member->avatar($member['member_id'])); ?>
                </div>
                <?php
            }
        ?>
        <div class="clear"></div>
    </div>
</div>
<!--<a href="#" title="" class="more right">Все участники</a>-->
