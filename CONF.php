<?php
/**
 * Created by PhpStorm.
 * User: lovemybud
 * Date: 14/12/21
 * Time: 12:57
 */


class CONF {

    static private $__engine    =   'Yaconf';
    static private $__scope     =   'base';

    static public function set ($_key, $value) {

    }

    static public function get ($_key, $_scope = NULL) {
        if (NULL == $_scope) {
            return \Yaconf::get($_key . '-' . $_scope);
        } else {
            return \Yaconf::get($_key);
        }

    }

    static public function has ($_key, $_scope = NULL) {
        if (NULL == $_scope) {
            return \Yaconf::has($_key . '-' . $_scope);
        } else {
            return \Yaconf::has($_key);
        }
    }

    static public function set_scope ($_scope = 'base') {
        self::$__scope          =   $_scope;
    }
}