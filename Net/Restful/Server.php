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

    function __construct(Server\Handle $_object, $_config) {
        $this->__config                     =   $_config;
        $this->add_service($_object);
    }

    public function add_service(Server\Handle $_object) {
        $this->__service_instance           =   $_object;
    }

    public function handle() {

        $__return_value                     =   NULL;
        $__tmp_instance                     =   $this->__service_instance;
        $__tmp_request                      =   $this->__request;

        $__tmp_url                          =   $__tmp_instance->__url ?? $_SERVER['REQUEST_URI'];
        $__tmp_service                      =   explode('?', $__tmp_url, 2);
        $__tmp_hdr_pty                      =   $this->__parse_header_and_properties($_SERVER, $this->__config['request']['properties']);

        $__tmp_request                      =   [
            'method'        =>  $_SERVER['REQUEST_METHOD'],
            'url'           =>  $__tmp_url,
            'service'       =>  $__tmp_service[0] ?? '',
            'resource'      =>  '',
            'parameters'    =>  $__tmp_service[1] ?? '',
            'properties'    =>  $__tmp_hdr_pty['properties'],
            'headers'       =>  $__tmp_hdr_pty['headers'],
            'body'          =>  file_get_contents('php://input')
        ];

        $__tmp_request['resource']          =   (empty($__tmp_instance->__serviceholder) ? '' : $this->__parse_resource($__tmp_instance->__serviceholder, $__tmp_instance->__resource_locate, $__tmp_request));

        $this->__request                    =   $__tmp_request;

        switch($__tmp_request['method']) {
            case EX_NET_RESTFUL_METHOD_GET:     $__return_value =   $__tmp_instance->GET    ($__tmp_request['service'], $__tmp_request['resource'], $__tmp_request['parameters'], $__tmp_request['properties']);    break;
            case EX_NET_RESTFUL_METHOD_DELETE:  $__return_value =   $__tmp_instance->DELETE ($__tmp_request['service'], $__tmp_request['resource'], $__tmp_request['parameters'], $__tmp_request['properties']);    break;
            case EX_NET_RESTFUL_METHOD_HEAD:    $__return_value =   $__tmp_instance->HEAD   ($__tmp_request['service'], $__tmp_request['resource'], $__tmp_request['parameters'], $__tmp_request['properties']);    break;
            case EX_NET_RESTFUL_METHOD_TRACE:   $__return_value =   $__tmp_instance->TRACE  ($__tmp_request['service'], $__tmp_request['resource'], $__tmp_request['parameters'], $__tmp_request['properties']);    break;
            case EX_NET_RESTFUL_METHOD_OPTIONS: $__return_value =   $__tmp_instance->OPTIONS($__tmp_request['service'], $__tmp_request['resource'], $__tmp_request['parameters'], $__tmp_request['properties']);    break;
            case EX_NET_RESTFUL_METHOD_POST:    $__return_value =   $__tmp_instance->POST   ($__tmp_request['service'], $__tmp_request['resource'], $__tmp_request['parameters'], $__tmp_request['properties'], $__tmp_request['body']);    break;
            case EX_NET_RESTFUL_METHOD_PUT:     $__return_value =   $__tmp_instance->PUT    ($__tmp_request['service'], $__tmp_request['resource'], $__tmp_request['parameters'], $__tmp_request['properties'], $__tmp_request['body']);    break;
            case EX_NET_RESTFUL_METHOD_PATCH:   $__return_value =   $__tmp_instance->PATCH  ($__tmp_request['service'], $__tmp_request['resource'], $__tmp_request['parameters'], $__tmp_request['properties'], $__tmp_request['body']);    break;
            case EX_NET_RESTFUL_METHOD_UPDATE:  $__return_value =   $__tmp_instance->UPDATE ($__tmp_request['service'], $__tmp_request['resource'], $__tmp_request['parameters'], $__tmp_request['properties'], $__tmp_request['body']);    break;
            default:
                break;
        }

        if (FALSE !== $__return_value) $this->__respond($__tmp_instance->get_response());
    }

    public function get_request() {
        return $this->__request;
    }

    private function __parse_header_and_properties($_source, $_properties_config) {
        $__return_value                     =   [
            'properties'    =>  [],
            'headers'       =>  []
        ];

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

        foreach ($_properties_config as $__property_k => $__property_v) {
            if (!is_array($__property_v)){
                $__return_value['properties'][$__property_k]    =   [
                    'key'   =>  $__property_v,
                    'value' =>  ''
                ];
            }
        }

        /* PARSE HEADER END }}} */

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

    private function __respond(Server\Response $_response) {

        $__tmp_http_version                     =   '';
        $__tmp_properties                       =   $this->__config['response']['properties'];
        $__tmp_headers                          =   $_response->headers;
        $__header                               =   '';
        $__content_type                         =   $_response->content_type;
        $__response_data                        =   $_response->data;
        $__response_body                        =   '';

        switch($_response->http_version) {
            case EX_NET_HTTP_VERSION_1_0:   $__tmp_http_version =   'HTTP/1.0'; break;
            case EX_NET_HTTP_VERSION_2_0:   $__tmp_http_version =   'HTTP/2.0'; break;
            case EX_NET_HTTP_VERSION_1_1:
            default:
                $__tmp_http_version =   'HTTP/1.1';
                break;
        }
        header($__tmp_http_version . ' ' . $_response->status . ' ' . $_response->message);


        if (!empty($__tmp_headers)) {
            foreach ($__tmp_headers as $__header) {
                header($__header);
            }
        }


        if (!empty($__tmp_properties)) {
            foreach ($__tmp_properties as $_k => $_v) {
                if (isset($_response->properties[$_k])) {
                    $__header                   =   (is_array($_v) ? $_v['key'] : $_v) . ': ' . $_response->properties[$_k];
                }
                elseif (is_array($_v)) {
                    $__header                   =   $_v['key'] . ': ' . ($_v['value'] ?? '');
                }
                else {
                    $__header                   =   $_v . ': ';
                }
                header($__header);
            }
        }

        if (!empty($__content_type)) {
            header('Content-Type: ' . $__content_type . '; charset=' . $_response->charset);
        }

        if (!empty($__response_data)) {

            switch ($_response->content_type) {
                case EX_MIMETYPE_JSON:
                    $__response_body            =   $this->_parse_json($_response->data, $this->__config['response']['content_type']['json'] ?? []);
                    break;

                case EX_MIMETYPE_MSGPACK:
                    $__response_body            =   msgpack_pack($_response->data);
                    break;

                case EX_MINETYPE_PLAIN:
                default:
                $__response_body                =   $_response->data;
                    break;
            }

            echo $__response_body;
        }
    }

    protected function _parse_json($_data, $_config) {
        return json_encode($_data, $_config['options'] ?? 0);
    }
}