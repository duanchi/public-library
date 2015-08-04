<?php
/**
 * Created by PhpStorm.
 * User: lovemybud
 * Date: 15/8/4
 * Time: 23:52
 */

namespace Net\Restful\Server;


abstract class Handler
{
    private $__request;
    private $__request_headers;
    private $__request_body;
    private $__service;
    private $__resource;
    private $__properties;

    function __construct($_service_regex = '', $_resource_locate = EX_NET_RESTFUL_OPT_RESLOCURL) {}

    public function GET($_service, $_resource, $_properties) {}

    public function DELETE() {}

    public function HEAD() {}

    public function TRACE() {}

    public function OPTINOS() {}

    public function POST() {}

    public function PUT() {}

    public function PATCH() {}

    public function UPDATE() {}

    public function get_request() {
        return $this->__request;
    }

    public function get_request_headers() {
        return $this->__request_headers;
    }

    public function handle() {
        /*{{{ PARSE RESOURCE START*/

        /*PARSE RESOURCE END}}}*/
    }
}