<?php
/**
 * Created by PhpStorm.
 * User: lovemybud
 * Date: 15/3/15
 * Time: 23:18
 */

namespace Data;


class DataAbstract {

    const DATA_PATH                     =   APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . '_data';

    static protected $__instance        =   NULL;
    static protected $__initialized     =   [];
    static protected $__DATA_TYPE       =   NULL;
    static protected $__SUFFIX          =   NULL;

    static public function get ($_name) {
        if ($_name != NULL) self::initialize($_name);

        return self::$__instance->get($_name);
    }

    static public function set ($_name, $_data) {
        if ($_name != NULL) self::initialize($_name);

        return self::$__instance->set($_name, static::encode_data($_data));
    }

    public function __get($_name) {
        return self::get($_name);
    }

    public function __set($_name, $_data) {
        return self::set($_name, $_data);
    }

    static protected function initialize($_name) {

        if (self::$__instance === NULL)
            self::$__instance           =   new \Yac(self::$__DATA_TYPE);


        if (isset(self::$__initialized[$_name])) return TRUE;

        self::$__instance->set(
                                $_name,
                                static::decode_data(
                                                    self::get_data_file($_name)
                                                )
                            );

        return TRUE;
    }

    static protected function get_data_file($_name) {
        return file_get_contents(self::DATA_PATH . DIRECTORY_SEPARATOR . static::$__DATA_TYPE . DIRECTORY_SEPARATOR . $_name . static::$__SUFFIX);
    }

    static protected function decode_data($_data) {
        return $_data;
    }

    static protected function encode_data($_data) {
        return $_data;
    }
}