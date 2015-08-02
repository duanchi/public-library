<?php
namespace Net\Restful\Client;


class Request extends \Net\Http\Client\Request
{

    protected $_method          =   EX_NET_RESTFUL_METHOD_GET;
    protected $_service         =   '[SERVICE NOT SET]';
    protected $_resource        =   '';

    public function __construct(string $_method = '', string $_service = '', $_resource = '', array $_properties = [], array $_request_headers = [], string $_request_body = '', array $_configurations = []) {

        $this->_method                          =   $_method    ??  $this->_method;
        $this->_service                         =   $_service   ??  $this->_service;
        $this->_resource                        =   $_resource  ??  $this->_resource;

        $this->__config                         =   $this->__get_config();

        parent::__construct(
            $_method,
            $this->__parse_request_url($_service, $_resource),
            $_request_body,
            array_merge(
                $_request_headers,
                isset($this->__config['properties']) ? $this->__parse_properties($_properties, $this->__config['properties']) : []
            ),
            $_configurations
        );
    }

    private function __get_config() {
        return \CONF::get('restful', NULL, PUBLIC_LIBRARY_KEY);
    }

    private function __parse_properties($_properties, $_config) {

        $__return_value                             =   [];

        if (!empty($_config)) {
            foreach ($_config as $__property_k => $__property_v) {
                if (isset($_properties[$__property_k])) {
                    $__return_value[]               =   [
                        is_string($__property_v) ? $__property_v['key'] : $__property_v
                        =>  $_properties[$__property_k]
                    ];
                }
                elseif (isset($__property_v['key'])) {
                    $__return_value[]               =   [
                        $__property_v['key']
                        =>  isset($__property_v['value']) ? $__property_v['value'] : ''
                    ];
                }
                elseif (is_string($__property_v)) {
                    $__return_value[]               =   [
                        $__property_v   =>  ''
                    ];
                }
            }
        }

        return $__return_value;
    }

    private function __parse_request_url($_url, $_resource = NULL) {

        $__return_value                         =   $_url;

        if (!empty($_resource)) {
            $__has_resource_placeholder         =   0;
            $__return_value                     =   str_replace($this->__config['resource']['placeholder'], $_resource, $_url, $__has_resource_placeholder);

            if (0 === $__has_resource_placeholder) {
                $__return_value                 =   rtrim($_url, '/') . '/'. $_resource;
            }
        }

        return $__return_value;
    }

}