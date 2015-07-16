<?php

/**
 * Created by PhpStorm.
 * User: lovemybud
 * Date: 15/7/14
 * Time: 23:14
 */
namespace Net\Restful\Request;
class Header
{
    protected $_header     =   [];

    function __construct() {

    }

    public function set($_key, $_value = NULL) {

        $_result_value          =   $this;

        $__node                 =   [];

        if (empty($_key)) {
            return $_result_value;
        }

        elseif (is_array($_key)) {
            foreach ($_key as $__k => $__v) {
                $this->_header[$__k]=   $__v;
            }
        }

        else {
            $this->_header[$_key]=   $_value;
        }
        return $_result_value;
    }

    public function get($_key) {
        return (!isset($this->_header[$_key]) ? NULL : $this->_header[$_key]);
    }


    public function __set($_key, $_value = NULL) {
        return $this->set($_key, $_value);
    }

    public function __get($_key) {
        return $this->get($_key);
    }
}