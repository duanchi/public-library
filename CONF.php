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


    /**
     * @todo support with get
     * @param $_key
     * @param $value
     */
    static public function set ($_key, $value) {

    }

    /**
     * get config vaules with scope,key,environment,applications
     * @param $_scope
     * @param string $_key
     * @param null $_environment
     * @param null $_application
     * @return mixed
     */
    public static function get ($_scope, $_key = '', $_environment = NULL, $_application = NULL) {

        NULL    ==  $_application   ?   $_application   =   self::$__application    :   FALSE;
        ''      ==  $_key           ?   TRUE                                        :   $_key    =   '.' . $_key;
        NULL    ==  $_environment   ?   $_environment   =   self::$__environment    :   FALSE;

        if (NULL == $_application) {
            return NULL;
        }

        $__return_value     =   \Yaconf::get($_application . self::SCOPE_SEPARATOR . $_environment . self::SCOPE_SEPARATOR . $_scope . $_key);

        return $__return_value;

    }


    /**
     * check config if or not exist with scope,key,environment,applications
     * @param $_scope
     * @param string $_key
     * @param null $_environment
     * @param null $_application
     * @return boolean
     */
    public static function has ($_scope, $_key = '', $_environment = NULL, $_application = NULL) {

        NULL    ==  $_application   ?   $_application   =   self::$__application    :   FALSE;
        ''      ==  $_key           ?   TRUE                                        :   $_key    =   '.' . $_key;
        NULL    ==  $_environment   ?   $_environment   =   self::$__environment    :   FALSE;

        if (NULL == $_application) {
            return NULL;
        }

        return \Yaconf::has($_application . self::SCOPE_SEPARATOR . $_environment . self::SCOPE_SEPARATOR . $_scope . $_key);
    }

    /**
     * set environment of the config scope
     * @param string $_environment
     * @param string $_application
     */
    static public function set_environment ($_environment = 'base', $_application = NULL) {
        self::$__environment        =   $_environment;
        self::$__application        =   $_application;
    }
}