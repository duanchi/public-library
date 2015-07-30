<?php
/**
 * Created by PhpStorm.
 * User: lovemybud
 * Date: 15/7/14
 * Time: 21:25
 */

namespace Net\Http\Client;


class Request
{

    private $__instance                     =   NULL;
    private $__configurations               =   [];

    public function __construct(string $_method = EX_NET_HTTP_METHOD_GET, string $_url = '', string $_request_body = '', array $_request_headers = [], array $_configurations = []) {

        $this->__instance                   =   curl_init($_url);
        $__tmp_configurations               =   [];

        $__tmp_configurations[CURLOPT_CUSTOMREQUEST]    =   $_method;

        if (
            ($_method == EX_NET_HTTP_METHOD_POST || $_method == EX_NET_HTTP_METHOD_UPDATE || $_method == EX_NET_HTTP_METHOD_PATCH)
            &&
            !empty($_request_body)
        ) {
            $__tmp_configurations[CURLOPT_POSTFIELDS]   =   $_request_body;
        }

        if (!empty($_configurations)) {
            $__tmp_configurations          +=   $_configurations;
        }

        $this->set($__tmp_configurations);
    }

    public function set($_key, $_value = NULL) {

        if (empty($_key)) return FALSE;

        if (is_array($_key)) {
            $this->__configurations        +=   $_key;
        }
        else {
            $this->__configurations        +=   [$_key=>$_value];
        }
        return TRUE;
    }

    public function get($_key) {
        return $this->__configurations[$_key];
    }

    public function __set($_key, $_value = NULL) {
        return $this->set($_key, $_value);
    }

    public function __get($_key) {
        return $this->get($_key);
    }
}