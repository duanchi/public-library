<?php
namespace Net\Restful\Client;


class Request extends \Net\Http\Client\Request
{

    private $__service              =   '';
    private $__resource             =   '';
    private $__parameter            =   '';
    private $__properties           =   [];

    public function __construct(string $_method = EX_NET_RESTFUL_METHOD_GET, string $_service = '[SERVICE NOT SET]', $_resource = '', $_parameter = '', array $_properties = [], array $_request_headers = [], string $_request_body = '', array $_configurations = []) {

        $this->__service            =   $_service;
        $this->__resource           =   $_resource;
        $this->__parameter          =   $_parameter;
        $this->__properties         =   $_properties;

        parent::__construct(
            $_method,
            '',
            $_request_body,
            $_request_headers,
            $_configurations
        );
    }

    public function get_service() {
        return $this->__service;
    }

    public function get_resource() {
        return $this->__resource;
    }

    public function get_parameter() {
        return $this->__parameter;
    }

    public function get_properties() {
        return $this->__properties;
    }

}