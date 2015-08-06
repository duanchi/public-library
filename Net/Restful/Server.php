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

    function __construct(Server\Handler $_object) {
        $this->add_service($_object);
    }

    public function add_service(Server\Handler $_object) {
        $this->__service_instance           =   $_object;
    }

    public function handle() {
        $this->__service_instance->handle();
    }
}