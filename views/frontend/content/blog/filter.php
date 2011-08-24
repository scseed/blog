<?php defined('SYSPATH') or die('No direct access allowed.');?>
<ul id="filters">
    <?php
    foreach (array('all', 'popular', 'discussed') as $val) {
    ?>
    <li <?php if ($current==$val) echo 'class="active"';?>>
        <?php echo HTML::anchor(Request::initial()->uri().URL::query(array('filter'=> $val)),
                                __('filter_'.$val), array('title' => ''))?>
    </li>
    <?php
    }
    ?>
</ul>
<br />