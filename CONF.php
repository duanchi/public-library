<?php
/**
 * Created by PhpStorm.
 * User: lovemybud
 * Date: 14/12/21
 * Time: 12:57
 */


class CONF {

    static private $__engine    =   'yaconf';

    static public function set ($_key, $value) {

    }

    static public function get($_key) {
        return \Yaconf::get($_key);
    }

    static public function has($_key) {
        return \Yaconf::has($_key);
    }
}