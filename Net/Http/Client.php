<?php
namespace Net\Http;


class Client
{

    private $__instance                 =   NULL;
    private $__request_instances        =   [];
    private $__response_instances       =   [];

    public function __construct(array $_configuration  = []) {
        $_configuration['pipelining']   =   TRUE;

        $this->configure($_configuration);
    }

    public function add_request(Client\Request $_request, $_callback_func = NULL) {
        $__request_uuid                 =   \Core\UUID::make(EX_CORE_UUID_TYPE_RANDOM);
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
            $this->__configurations    +=   $_key;
        }
        else {
            $this->__configurations    +=   [$_key=>$_value];
        }
        return TRUE;
    }

    public function execute($_exec_type = EX_NET_HTTP_REQUEST_CONCURRENT) {

        $__instance                     =   NULL;
        $__return_value                 =   [];

        if (1 === count($this->__request_instances)) {

            foreach ( $this->__request_instances as $__request_k  => $__request_v) {
                if ($__request_v['status'] == EX_HTTP_CLIENT_STATUS_INIT) {

                    $__request_v['status']  =   EX_HTTP_CLIENT_STATUS_EXEC;

                    curl_exec($__request_v->get_instance());
                    $__return_value[$__request_k]                       =   $this->__get_response($__request_v->get_instance());

                    $__request_v['status']  =   EX_HTTP_CLIENT_STATUS_RESPONED;
                }
            }

        }
        else {
            $__instance                 =   curl_multi_init();
            $__active                   =   NULL;
            $__tmp_request_hash         =   [];

            foreach ($this->__request_instances as $__request_k => $__request_v) {
                if ($__request_v['status'] == EX_HTTP_CLIENT_STATUS_INIT) {
                    curl_multi_add_handle($__instance, $__request_v->get_instance());
                    $__tmp_request_hash[]                               =   $__request_k;
                    $this->__request_instances[$__request_k]['status']  = EX_HTTP_CLIENT_STATUS_EXEC;
                }
            }


            /* {{{ MULTI CURL EXEC START */
            do {
                $__mrc = curl_multi_exec($__instance, $__active);
            } while ($__mrc == CURLM_CALL_MULTI_PERFORM);

            while ($__active && $__mrc == CURLM_OK) {
                if (-1 == curl_multi_select($__instance)) {
                    usleep(100);
                }
                do {
                    $__mrc = curl_multi_exec($__instance, $__active);
                } while ($__mrc == CURLM_CALL_MULTI_PERFORM);
            }
            /* }}} MULTI CURL EXEC END */

            while ($__tmp_hash = array_pop($__tmp_request_hash)) {

                $this->__request_instances[$__tmp_hash]['status']       =   EX_HTTP_CLIENT_STATUS_RESPONED;
                $__return_value[$__tmp_hash]                            =   $this->__get_response($this->__request_instances[$__tmp_hash]['request']->get_instance());
                curl_multi_remove_handle($__instance, $this->__request_instances[$__tmp_hash]['request']->get_instance());

                /* {{{ EXEC CALLBACK FUNCTION START */
                try {
                    $this->__request_instances[$__tmp_hash]['callback'](
                        $__tmp_hash,
                        $__return_value[$__tmp_hash]['status'],
                        $this->__request_instances[$__tmp_hash]['request'],
                        $__return_value[$__tmp_hash]
                    );
                    $__return_value[$__tmp_hash]['callback_status']     =   TRUE;
                } catch (\Exception $_e) {
                    $__return_value[$__tmp_hash]['callback_status']     =   FALSE;
                }
                /* EXEC CALLBACK FUNCTION END }}} */
            }

            curl_multi_close($__instance);
        }


        $this->__response_instances                                     =   $__return_value;

        return $__return_value;
    }

    private function __get_response($_handle) {

        $__return_value                     =   [
            'status'        =>  -1,
            'message'       =>  '',
            'info'          =>  [],
            'response'      =>  '',
            'error_code'    =>  -1,
            'error_message' =>  'No Curl Handle Executed.'
        ];

        $__error_code                       =   curl_errno($_handle);

        if (!empty($_handle) && 0 == $__error_code) {

            $__return_value['info']         =   curl_getinfo($_handle);
            $__return_value['status']       =   $__return_value['http_code'];
            $__return_value['message']      =   (defined('EX_NET_HTTP_CODE_' . $__return_value['status']) ? get_defined_constants('EX_NET_HTTP_CODE_' . $__return_value['status']) : '');
            $__return_value['response']     =   curl_multi_getcontent($_handle);

        }
        elseif (!empty($_handle) && $__error_code) {

            $__return_value['error_code']   =   $__error_code;
            $__return_value['error_message']=   curl_error($_handle);

        }

        return $__return_value;
    }

    public function __call($_method_name, $_arguments) {
        return $this->__instance->{$_method_name}(...$_arguments);
    }
}