<?php
/**
 * Created by PhpStorm.
 * User: lovemybud
 * Date: 15/7/14
 * Time: 21:25
 */

namespace Net\Restful;


class Request
{

    protected $_method          =   EX_NET_RESTFUL_METHOD_GET;
    protected $_service         =   '[SERVICE NOT SET]';
    protected $_resource        =   '';
    protected $_parameter       =   NULL;
    protected $_http_version    =   EX_NET_HTTP_VERSION_1_1;

    protected $_host            =   '';
    protected $_accept          =   EX_MIMETYPE_MSGPACK;
    protected $_accept_encoding =   '';
    protected $_accept_charset  =   EX_CHARSET_UTF8;
    protected $_user_agent      =   '';
    protected $_access_token    =   '';
    protected $_client_id       =   '';
    protected $_version         =   '';
    protected $_ranges          =   '';

    protected $_request_line    =   '';
    protected $_request_header  =   NULL;
    protected $_request_body    =   '';

    function __construct() {
        $this->_request_header  =   new Request\Header();
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
        $this->_request_line                =   $this->_method . ' ' . $this->_service . '/' . $this->_resource . $this->_parameter . ' ' . $this->_http_version;

        return $_return_value;
    }
}