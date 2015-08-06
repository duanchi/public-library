<?php
/**
 * Created by PhpStorm.
 * User: lovemybud
 * Date: 15/7/14
 * Time: 21:25
 */

namespace Net\Restful;


class Server
{

    private $__service_instance             =   NULL;
    private $__config                       =   [];

    private $__request                      =   [
        'method'        =>  EX_NET_RESTFUL_METHOD_GET,
        'url'           =>  '',
        'service'       =>  '',
        'resource'      =>  '',
        'parameters'    =>  '',
        'properties'    =>  [],
        'headers'       =>  [],
        'body'          =>  ''
    ];

    function __construct(Server\Handler $_object, $_config) {
        $this->__config                     =   $_config;
        $this->add_service($_object);
        t($_config);
    }

    public function add_service(Server\Handler $_object) {
        $this->__service_instance           =   $_object;
    }

    public function handle() {

        t($this->__config);
        $__tmp_instance                     =   $this->__service_instance;

        $__tmp_url                          =   $__tmp_instance->__url ?? $_SERVER['REQUEST_URI'];
        $__tmp_service                      =   explode('?', $__tmp_url, 2);
        $__tmp_hdr_pty                      =   $this->__parse_header_and_properties($_SERVER, $this->__config['properties']);

        /*$this->__request                    =   [
            'method'        =>  $_SERVER['REQUEST_METHOD'],
            'url'           =>  $__tmp_url,
            'service'       =>  $__tmp_service[0] ?? '',
            'resource'      =>  '',
            'parameters'    =>  $__tmp_service[1] ?? '',
            'properties'    =>  $__tmp_hdr_pty['properties'],
            'headers'       =>  $__tmp_hdr_pty['headers'],
            'body'          =>  file_get_contents('php://input')
        ];

        $this->__request['resource']        =   (empty($this->__serviceholder) ? '' : $this->__parse_resource($__tmp_instance->__serviceholder, $__tmp_instance->__resource_locate, $this->__request));*/

        //$__tmp_instance->handle($this->__request);
    }

    public function get_request() {
        return $this->__request;
    }

    private function __get_config() {
        return \CONF::get('restful', NULL, NULL, PUBLIC_LIBRARY_KEY);
    }

    private function __parse_header_and_properties($_source, $_properties_config) {
        $__return_value                     =   [
            'properties'    =>  [],
            'headers'       =>  []
        ];

        t($_properties_config);
        /* {{{ PARSE HEADER START */
        foreach ($_source as $_server_k => $_server_v) {

            switch($_server_k) {
                case 'PHP_AUTH_DIGEST':     $_server_k    =   'HTTP_AUTHORIZATION'; break;
                case 'PHP_AUTH_USER':       $_server_k    =   'HTTP_AUTH_USER';     break;
                case 'PHP_AUTH_PW':         $_server_k    =   'HTTP_AUTH_PW';       break;
                case 'CONTENT_LENGTH':      $_server_k    =   'HTTP_CONTENT_LENGTH';break;
                case 'CONTENT_TYPE':        $_server_k    =   'HTTP_CONTENT_TYPE';  break;
            }

            if (0 === strpos($_server_k, 'HTTP_')) {
                $__tmp_key                  =   strtolower(substr($_server_k, 5));

                $__tmp_header_key           =   implode(
                    '-',
                    array_map(
                        function($__v){return ucfirst($__v);},
                        explode('_', $__tmp_key)
                    )
                );

                if (isset($_properties_config[$__tmp_key])) {
                    $__return_value['properties'][$__tmp_key]   =   [
                        'key'   =>  $__tmp_header_key,
                        'value' =>  $_server_v
                    ];
                    unset($_properties_config[$__tmp_key]);
                }

                $__return_value['headers'][]                    =   $__tmp_header_key . ': ' . $_server_v;
            }
        }

        /*foreach ($_properties_config as $__property_k => $__property_v) {
            if (!is_array($__property_v)){
                $__return_value['properties'][$__property_k]    =   [
                    'key'   =>  $__property_v,
                    'value' =>  ''
                ];
            }
        }*/

        /* PARSE HEADER END }}} */

        //t($_properties_config);
        return $__return_value;
    }

    private function __parse_resource($_serviceholder, $_resource_locate, $_request) {

        $__return_value                     =   '';

        switch ($_resource_locate) {
            case EX_NET_RESTFUL_OPT_RESLOCPRY:
                if (is_string($_serviceholder)) {
                    $__return_value         =   $_request['properties'][$_serviceholder]['value'] ?? '';
                }
                elseif (isset($_serviceholder['key']) && isset($_serviceholder['regex'])) {
                    $__tmp_match            =   [];
                    preg_match($_serviceholder['regex'], $_request['properties'][$_serviceholder['key']]['value'], $__tmp_match);

                    $__return_value         =   $__tmp_match[1] ?? '';
                }
                elseif (isset($_serviceholder['key'])) {
                    $__return_value         =   $_request['properties'][$_serviceholder['key']]['value'];
                }
                break;

            case EX_NET_RESTFUL_OPT_RESLOCHDR:
                if (!empty($_request['headers']) && !empty($_serviceholder)) {
                    foreach ($_request['headers'] as $__header) {
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
                    if (preg_match($_serviceholder, $_request['url'], $__tmp_match)) {
                        $__return_value         = $__tmp_match[1] ?? '';
                    }
                }
                break;
        }

        return $__return_value;
    }
}