<?php
/**
 * Created by PhpStorm.
 * User: lovemybud
 * Date: 14/12/21
 * Time: 12:57
 */


class CONF {

    const SCOPE_SEPARATOR           =   '_';


    static private $__engine        =   'Yaconf';
    static private $__application   =   NULL;
    static private $__environment   =   'base';


    static public function set ($_key, $value) {

    }

    static public function get ($_scope, $_key = '', $_environment = NULL, $_application = NULL) {

        NULL    ==  $_application   ?   $_application   =   self::$__application    :   FALSE;
        ''      ==  $_key           ?   TRUE                                        :   $_key    =   '.' . $_key;
        NULL    ==  $_environment   ?   $_environment   =   self::$__environment    :   FALSE;

        if (NULL == $_application) {
            return NULL;
        }

        return \Yaconf::get($_application . self::SCOPE_SEPARATOR . $_scope . self::SCOPE_SEPARATOR . $_environment . $_key);

    }

    static public function has ($_scope, $_key = '', $_environment = NULL, $_application = NULL) {

        NULL    ==  $_application   ?   $_application   =   self::$__application    :   FALSE;
        ''      ==  $_key           ?   TRUE                                        :   $_key    =   '.' . $_key;
        NULL    ==  $_environment   ?   $_environment   =   self::$__environment    :   FALSE;

        if (NULL == $_application) {
            return NULL;
        }

        return \Yaconf::has($_application . self::SCOPE_SEPARATOR . $_scope . self::SCOPE_SEPARATOR . $_environment . $_key);
    }

    static public function set_environment ($_environment = 'base', $_application = NULL) {
        self::$__environment        =   $_environment;
        self::$__application        =   $_application;
    }
}