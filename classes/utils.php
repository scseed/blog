<?php
/**
 * Created by JetBrains PhpStorm.
 * User: admin
 * Date: 23.08.11
 * Time: 14:32
 * To change this template use File | Settings | File Templates.
 */
 
class Utils {

    public static function get_thumb($url) {
        $last_dot = strrpos($url, '.');
        return substr($url, 0, $last_dot) . 'thumb' . substr($url, $last_dot);
    }
}
