<?php
/**
 * Created by PhpStorm.
 * User: lovemybud
 * Date: 15/7/14
 * Time: 21:25
 */

namespace Net\Restful;


class Client
{

    private $__request_hash     =   [];

    function __construct() {
    }

    public function add_request(Request $_request, $_callback_func = NULL) {

    }

    public function execute($_exec_type = EX_NET_RESTFUL_REQUEST_CONCURRENT) {

        $_status                =   FALSE;
        $_response              =   NULL;









        //$this->__callback($_callback_func, $_status, $_response);
    }

    private function __callback($_callback_func, $_status, $_response) {

        if (empty($_callback_func)) {
            return FALSE;
        }

        return $_callback_func($_status, $_response);
    }
}