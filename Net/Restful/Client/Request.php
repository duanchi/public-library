<?php
namespace Net\Restful\Client;


class Request extends \Net\Http\Client\Request
{

    private $__config           =   [];

    public function __construct(string $_method = EX_NET_RESTFUL_METHOD_GET, string $_service = '[SERVICE NOT SET]', $_resource = '', $_parameter = '', array $_properties = [], array $_request_headers = [], string $_request_body = '', array $_configurations = []) {

        $this->__config                         =   $this->__get_config();

        parent::__construct(
            $_method,
            $this->__parse_request_url($_service, $_parameter, $_resource),
            $_request_body,
            array_merge(
                isset($this->__config['properties']) ? $this->__parse_properties($_properties, $this->__config['properties']) : [],
                $_request_headers
            ),
            $_configurations
        );
    }

    private function __get_config() {
        return \CONF::get('restful', NULL, NULL, PUBLIC_LIBRARY_KEY);
    }

    private function __parse_properties($_properties, $_config) {

        $__return_value                             =   [];

        if (!empty($_config)) {
            foreach ($_config as $__property_k => $__property_v) {
                if (isset($_properties[$__property_k])) {
                    $__return_value[]               =   (is_array($__property_v) ? $__property_v['key'] : $__property_v) . ': '. $_properties[$__property_k];
                }
                elseif (isset($__property_v['key'])) {
                    $__return_value[]               =   $__property_v['key'] . ': ' . (isset($__property_v['value']) ? $__property_v['value'] : '');
                }
                elseif (is_string($__property_v)) {
                    $__return_value[]               =   $__property_v . ': ';
                }
            }
        }

        return $__return_value;
    }

    private function __parse_request_url($_url, $_parameter, $_resource = NULL) {

        $__return_value                         =   $_url;
        $_url                                  .=   (empty($_parameter) ? '' : '?' . $_parameter);

        if (!empty($_resource)) {
            $__has_resource_placeholder         =   0;
            $__return_value                     =   str_replace($this->__config['resource']['placeholder'], $_resource, $_url, $__has_resource_placeholder);

            if (0 === $__has_resource_placeholder) {
                $__return_value                 =   (empty($_parameter) ? rtrim($_url, '/') . '/' : $_url . '&') . $_resource;
            }
        }

        return $__return_value;
    }

}