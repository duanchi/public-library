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
    protected $_service         =   '';
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

    protected $_header          =   NULL;

    protected $_request_body    =   '';

    function __construct() {
        $this->_header          =   new Request\Header();
    }

    public function set($_key, $_value = NULL) {

        $_result_value          =   $this;

        $__node                 =   [];

        if (empty($_key)) {
            return $_result_value;
        }
        elseif (is_array($_key)) {
            $__node             =   $_key;
        }
        else {
            $__node             =   [$_key => $_value];
        }

        foreach ($__node as $__k => $__v) {
            $__header_k         =   '';
            switch($__k) {

                case 'method':
                case 'service':
                case 'http_version':
                    $this->{'_'.$__k}     =   $__v;
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
                    $this->{'_'.$__k}     =   $__v;
                    $this->_header->set($__header_k, $__v);
                    break;
                
                case 'request_body':
                    $this->_request_body        =   $__v;
                    break;

                default:
                    $this->_header->set($__k, $__v);
                    break;
            }
        }
        return $_result_value;
    }

    public function get($_key) {

        $_return_value          =   NULL;

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
                $_return_value              =   $this->_header->get($_key);
                break;
        }
        return $_return_value;
    }

    public function set_header($_key, $_value = NULL) {
        $this->_header->set($_key, $_value);
    }

    public function get_header($_key) {
        return $this->_header->get($_key);
    }

    public function __set($_key, $_value = NULL) {
        return $this->set($_key, $_value);
    }

    public function __get($_key) {
        return $this->get($_key);
    }
}