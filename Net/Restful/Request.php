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

    protected $_method          =   EX_RESTFUL_METHOD_GET;
    protected $_service         =   '';
    protected $_http_version    =   EX_HTTP_VERSION_1_1;

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

    public function set($_key, $value = NULL) {

        $_result_value          =   $this;

        if (NULL != $value) {
            if (0 == count($_key)) {
                return $_result_value;
            }
            $_node              =   $_key;
        } else {
            $_node              =   [$_key, $value];
        }

        foreach ($_node as $k => $v) {
            $lk                 =   strtolower($k);
            switch($lk) {
                case 'method':

                    break;

                case 'service':

                    break;

                case 'http_version':

                    break;

                case 'user_agent':
                case 'user-agent':

                    break;

                case 'access_token':
                case 'access-token':

                    break;

                case 'client_id':
                case 'client-id':
                    break;

                case 'version':

                    break;

                case 'ranges':

                    break;

                case 'request_body':

                    break;

                default:

                    break;
            }

            set_header:

        }
        return $_result_value;
    }

    public function get($_key) {

        $_return_value          =   NULL;

        switch(strtolower($_key)) {
            case 'method':

                break;

            case 'service':

                break;

            case 'http_version':

                break;

            case 'user_agent':
            case 'user-agent':

                break;

            case 'access_token':
            case 'access-token':

                break;

            case 'client_id':
            case 'client-id':
                break;

            case 'version':

                break;

            case 'ranges':

                break;

            case 'request_body':

                break;

            default:

                break;
        }
        return $_return_value;
    }

    public function __set($_key, $_value) {
        return $this->set($_key, $_value);
    }

    public function __get($_key) {
        return $this->get($_key);
    }
}