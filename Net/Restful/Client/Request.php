<?php
/**
 * Created by PhpStorm.
 * User: lovemybud
 * Date: 15/7/14
 * Time: 21:25
 */

namespace Net\Restful\Client;


class Request
{

    protected $_method          =   EX_NET_RESTFUL_METHOD_GET;
    protected $_service         =   '[SERVICE NOT SET]';
    protected $_resource        =   '';
    protected $_parameters      =   NULL;
    protected $_http_version    =   EX_NET_HTTP_VERSION_1_1;

    private $__properties       =   [
        'accept'            =>  ['Accept', EX_MIMETYPE_MSGPACK],
        'accept_encoding'   =>  ['Accept-Encoding', EX_CHARSET_ENCODING_DEFAULT],
        'accept_charset'    =>  ['Accept-Charset',  EX_CHARSET_UTF8],
        'user_agent'        =>  ['User-Agent',  EX_NET_RESTFUL_USERAGENT_DEFAULT],
        'access_token'      =>  ['Access-Token', NULL],
        'client_id'         =>  ['Client-Id', NULL],
        'version'           =>  ['Version', NULL],
        'ranges'            =>  ['Ranges', NULL]
    ];

    private $__config           =   NULL;

    public function __construct(string $_method = '', string $_service = '', $_resource = '', array $_properties = [], string $_request_body = '', array $_ssl_options = []) {

        $this->__config                         =   $this->get_config();

        $this->__init_properties($_properties, $this->__config['properties']);

        !empty($_method) ? $this->_method       =   $_method : FALSE;
        !empty($_service) ? $this->_service     =   $_service : FALSE;
        !empty($_resource) ? $this->_resource   =   $_resource : FALSE;


        if (!empty($_resource)) {
            $__url                              =   $this->__update_request_url($_service, $_resource);
        }
        else {
            $__url                              =   $_service;
        }


        parent::__construct($_method, $__url, );
    }

    public function set($_key, $_value = NULL) {

        $_return_value                      =   $this;

        $__node                             =   [];

        if (empty($_key)) {
            return $_return_value;
        }
        elseif (is_array($_key)) {
            $__node                         =   $_key;
        }
        else {
            $__node                         =   [$_key => $_value];
        }

        foreach ($__node as $__k => $__v) {
            $__header_k                     =   '';
            switch($__k) {

                case 'service':
                case 'http_version':
                case 'method':
                    $this->{'_'.$__k}       =   $__v;
                    break;

                case 'host':
                case 'accept':
                case 'accept_encoding':
                case 'accept_charset':
                case 'user_agent':
                case 'access_token':
                case 'client_id':
                case 'version':
                case 'ranges':
                    $this->{'_'.$__k}       =   $__v;
                    $this->_request_header->set($__header_k, $__v);
                    break;
                
                case 'request_body':
                    $this->_request_body    =   $__v;
                    break;

                default:
                    $this->_request_header->set($__k, $__v);
                    break;
            }
        }
        return $_return_value;
    }

    public function get($_key) {

        $_return_value                      =   NULL;

        switch($_key) {
            case 'method':
            case 'service':
            case 'http_version':
            case 'host':
            case 'accept':
            case 'accept_encoding':
            case 'accept_charset':
            case 'user_agent':
            case 'access_token':
            case 'client_id':
            case 'version':
            case 'ranges':
                $_return_value              =   $this->{'_'.$_key};
                break;

            case 'request_body':
                $_return_value              =   $this->_request_body;
                break;

            default:
                $_return_value              =   $this->_request_header->get($_key);
                break;
        }
        return $_return_value;
    }

    public function set_header($_key, $_value = NULL) {
        $this->_request_header->set($_key, $_value);
    }

    public function get_header($_key) {
        return $this->_request_header->get($_key);
    }

    public function __set($_key, $_value = NULL) {
        return $this->set($_key, $_value);
    }

    public function __get($_key) {
        return $this->get($_key);
    }

    public function __toString() {

        $_return_value                      =   '';

        $__tmp_parameter                    =   $this->_parameter;
        $__tmp_parareter_string             =   '?';

        if (!empty($__tmp_parameter) and is_array($__tmp_parameter)) {
            foreach ($__tmp_parameter as $__k => $__v) {
                $__tmp_parareter_string    .=   $__k . (!empty($__v) ? '=' . $__v : '') . '&';
            }
            rtrim($__tmp_parareter_string, '&');
        }
        elseif (!empty($__tmp_parameter) and is_string($__tmp_parameter)) {
            $__tmp_parareter_string        .=   $__tmp_parameter;
        }
        else {
            $__tmp_parareter_string         =   '';
        }
        $this->_request_line                =   convert_constant('EX_NET_RESTFUL', $this->_method) . ' ' . $this->_service . '/' . $this->_resource . $this->_parameter . ' ' . convert_constant('EX_NET_HTTP_VERSION', $this->_http_version);

        $_return_value                      =   $this->_request_line .
                                                self::CRLF .
                                                $this->_request_header .
                                                (($this->_method >= EX_NET_HTTP_METHOD_POST) ?
                                                    self::CRLF . $this->_request_body . self::CRLF
                                                    :
                                                    ''
                                                );
        return $_return_value;
    }

    private function __get_config() {
        return \CONF::get('restful', NULL, PUBLIC_LIBRARY_KEY);
    }

    private function __init_properties($_properties, $_config) {

        if (!empty($_config)) {
            foreach ($_config as $__k => $__v) {
                if (isset($__v['key']) and isset($__v['value'])) {
                    $this->__properties[$__k]   =   [$__v['key'], $__v['value']];
                }
                elseif (isset($__v['key'])) {
                    $this->__properties[$__k]   =   [$__v['key'], NULL];
                }
                elseif (is_string($__v)) {
                    $this->__properties[$__k]   =   [$__v, NULL];
                }
            }
        }

        foreach ($this->__properties as $__k => $__v) {
            if (isset($_properties[$__k])) {
                $this->__properties[$__k]       =   $_properties[$__k];
                $this->set($_properties[$__k][0], $_properties[$__k][1]);
            }
            else {
                $this->set($__v[0], $__v[1]);
            }
        }
    }

    private function __update_request_url($_url, $_resource) {

        $_return_value                          =   '';

        $__has_resource_placeholder             =   0;
        $_return_value                          =   str_replace($this->__config['resource']['placeholder'], $_resource, $_url, $__has_resource_placeholder);

        if (0 === $__has_resource_placeholder) {
            $_return_value                      =   rtrim($_url, '/') . $_resource;
        }

        return $_return_value;
    }

}