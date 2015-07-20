<?php
/**
 * Created by PhpStorm.
 * User: lovemybud
 * Date: 15/7/14
 * Time: 21:25
 */

namespace Net\Restful\Client;


class Request extends \Net\Http\Client\Request
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

    public function __construct(string $_method = '', string $_service = '', $_resource = '', array $_properties = [], string $_request_body = '', array $_request_headers = [], array $_ssl_options = []) {

        $this->__config                         =   $this->__get_config();

        !empty($_method) ? $this->_method       =   $_method : FALSE;
        !empty($_service) ? $this->_service     =   $_service : FALSE;
        !empty($_resource) ? $this->_resource   =   $_resource : FALSE;

        parent::__construct(
            $_method,
            $this->__get_request_url($_service, $_resource),
            $_request_body,
            NULL,
            $_ssl_options
        );

        $this->__set_properties($_properties, $this->__config['properties']);

        $this->set($_request_headers);
    }

    private function __get_config() {
        return \CONF::get('restful', NULL, PUBLIC_LIBRARY_KEY);
    }

    private function __set_properties($_properties, $_config) {

        $_return_value                          =   [];

        if (!empty($_config)) {
            foreach ($_config as $__k => $__v) {
                if (isset($__v['key']) and isset($__v['value'])) {
                    $_return_value[$__k]        =   [$__v['key'], $__v['value']];
                }
                elseif (isset($__v['key'])) {
                    $_return_value[$__k]        =   [$__v['key'], NULL];
                }
                elseif (is_string($__v)) {
                    $_return_value[$__k]        =   [$__v, NULL];
                }
            }
        }

        foreach ($_return_value as $__k => $__v) {
            if (isset($_properties[$__k])) {
                $_return_value                  =   $_properties[$__k];
                $this->set($_properties[$__k][0], $_properties[$__k][1]);
            }
            else {
                $this->set($__v[0], $__v[1]);
            }
        }

        return $_return_value;
    }

    private function __get_request_url($_url, $_resource = NULL) {

        $_return_value                          =   $_url;

        if (!empty($_resource)) {
            $__has_resource_placeholder         =   0;
            $_return_value                      =   str_replace($this->__config['resource']['placeholder'], $_resource, $_url, $__has_resource_placeholder);

            if (0 === $__has_resource_placeholder) {
                $_return_value                  =   rtrim($_url, '/') . $_resource;
            }
        }

        return $_return_value;
    }

}