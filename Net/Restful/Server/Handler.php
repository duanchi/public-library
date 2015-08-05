<?php
/**
 * Created by PhpStorm.
 * User: lovemybud
 * Date: 15/8/4
 * Time: 23:52
 */

namespace Net\Restful\Server;


abstract class Handler
{
    private $__request                      =   [
        'url'           =>  '',
        'service'       =>  '',
        'resource'      =>  '',
        'parameters'    =>  '',
        'properties'    =>  [],
        'headers'       =>  [],
        'body'          =>  ''
    ];

    private $__serviceholder                =   NULL;
    private $__resource_locate              =   EX_NET_RESTFUL_OPT_RESLOCURL;
    private $__config                       =   [];

    function __construct($_url = NULL, $_serviceholder = NULL, $_resource_locate = EX_NET_RESTFUL_OPT_RESLOCURL) {
        $__tmp_url                          =   $_url ?? $_SERVER['REQUEST_URI'];
        $__tmp_url                          =   explode('?', $__tmp_url, 2);

        $this->__request                    =   [
            'url'           =>  $_url,
            'service'       =>  $__tmp_url[0] ?? '',
            'resource'      =>  '',
            'parameters'    =>  $__tmp_url[1] ?? '',
            'properties'    =>  [],
            'headers'       =>  [],
            'body'          =>  ''
        ];

        $this->__serviceholder              =   $_serviceholder;
        $this->__resource_locate            =   $_resource_locate;
        $this->__config                     =   $this->__get_config();

    }

    public function GET($_service, $_resource, $_parameters, $_properties) {}

    public function DELETE($_service, $_resource, $_parameters, $_properties) {}

    public function HEAD($_service, $_resource, $_parameters, $_properties) {}

    public function TRACE($_service, $_resource, $_parameters, $_properties) {}

    public function OPTIONS($_service, $_resource, $_parameters, $_properties) {}

    public function POST($_service, $_resource, $_parameters, $_properties, $_request_body) {}

    public function PUT($_service, $_resource, $_parameters, $_properties, $_request_body) {}

    public function PATCH($_service, $_resource, $_parameters, $_properties, $_request_body) {}

    public function UPDATE($_service, $_resource, $_parameters, $_properties, $_request_body) {}

    public function get_request() {
        return $this->__request;
    }

    public function handle($_request) {

        /* {{{ PARSE HEADER START */

        /* PARSE HEADER END }}} */

        /* {{{ PARSE PROPERTY START */

        /* PARSE PROPERTY END }}} */

        if (!empty($this->__serviceholder)) {
            $this->__request['resource']    =   $this->__parse_resource($this->__serviceholder, $this->__resource_locate, $this->__request);
        }



        /* {{{ PARSE RESOURCE START */

        /* PARSE RESOURCE END }}} */
    }

    private function __get_config() {
        return \CONF::get('restful', NULL, NULL, PUBLIC_LIBRARY_KEY);
    }

    private function __parse_resource($_serviceholder, $_resource_locate, $_request) {

        $__return_value                     =   '';

        switch ($_resource_locate) {
            case EX_NET_RESTFUL_OPT_RESLOCPRY:
                if (is_string($_serviceholder)) {
                    $__return_value         =   $this->__request['properties'][$_serviceholder]['value'] ?? '';
                }
                elseif (isset($_serviceholder['key']) && isset($_serviceholder['regex'])) {
                    $__tmp_match            =   [];
                    preg_match($_serviceholder['regex'], $this->__request['properties'][$_serviceholder['key']]['value'], $__tmp_match);

                    $__return_value         =   $__tmp_match[1] ?? '';
                }
                elseif (isset($_serviceholder['key'])) {
                    $__return_value         =   $this->__request['properties'][$_serviceholder['key']]['value'];
                }
                break;

            case EX_NET_RESTFUL_OPT_RESLOCHDR:
                if (!empty($this->__request['headers']) && !empty($_serviceholder)) {
                    foreach ($this->__request['headers'] as $__header) {
                        $__tmp_match            =   [];
                        if (preg_match($_serviceholder, $__header, $__tmp_match)) {
                            $__return_value     =   $__tmp_match[1] ?? '';
                            break;
                        }
                    }
                }
                break;

            case EX_NET_RESTFUL_OPT_RESLOCURL:
            default:
                if (!empty($_serviceholder)) {
                    $__tmp_match                =   [];
                    if (preg_match($_serviceholder, $this->__request['url'], $__tmp_match)) {
                        $__return_value         = $__tmp_match[1] ?? '';
                    }
                }
                break;
        }

        return $__return_value;
    }
}