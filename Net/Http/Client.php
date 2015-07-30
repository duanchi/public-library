<?php
namespace Net\Http;


class Client
{

    private $__instance             =   NULL;
    private $__request_instances    =   [];
    private $__response_instances   =   [];

    public function __construct(array $_configuration  = []) {
        $_configuration['pipelining']   =   TRUE;

        $this->configure($_configuration);
    }

    public function add_request(Client\Request $_request, $_callback_func = NULL) {
        $__request_uuid         =   \Core\UUID::make(EX_CORE_UUID_TYPE_RANDOM);
        $this->__request_instances[$__request_uuid] =   [
            'request'   =>  $_request,
            'callback'  =>  $_callback_func,
            'status'    =>  EX_HTTP_CLIENT_STATUS_INIT
        ];
        $this->__request_instances[$__request_uuid] =   NULL;

        return $this;
    }

    public function configure($_key, $_value = NULL) {
        if (empty($_key)) return FALSE;

        if (is_array($_key)) {
            $this->__configurations        +=   $_key;
        }
        else {
            $this->__configurations        +=   [$_key=>$_value];
        }
        return TRUE;
    }

    public function execute($_exec_type = EX_NET_HTTP_REQUEST_CONCURRENT) {

        foreach($this->__request_hash as $__k => $__v) {
            if (EX_HTTP_CLIENT_STATUS_INIT === $__v['status']) {
                $__callback_func            =   $__v['callback'];
                t($__v);
                /*$this->__instance->enqueue(

                    $__v['request']

                    function($_response) use ($__k, $__v) {
                        return $__v['callback_func']($__k, $_response->getResponseCode(), $__v['request'], $_response);
                    }

                );
                $this->__request_hash[$__k]['status']   =   EX_HTTP_CLIENT_STATUS_EXEC;*/
            }
        }

        /*$this->__instance->send();

        foreach($this->__request_hash as $__k => $__v) {
            if (EX_HTTP_CLIENT_STATUS_EXEC === $__v['status']) {
                $this->__response_hash[$__k]            =   $this->getResponse($__v['request']);
                $this->__request_hash[$__k]['status']   =   EX_HTTP_CLIENT_STATUS_RESPONED;
            }
        }*/

        return $this->__response_hash;
    }

    public function __call($_method_name, $_arguments) {
        return $this->__instance->{$_method_name}(...$_arguments);
    }
}