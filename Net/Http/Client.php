<?php
namespace Net\Http;


class Client
{

    private $__instance                 =   NULL;
    private $__request_instances        =   [];
    private $__response_instances       =   [];
    private $__configurations           =   [
        EX_NET_HTTP_OPT_PIPELINING      =>  TRUE,
        EX_NET_HTTP_OPT_MAXCONNECTS     =>  10
    ];

    public function __construct(array $_configuration  = []) {
        $this->configure($_configuration);
    }

    public function add_request(Client\Request $_request, $_callback_func = NULL) {
        $__request_uuid                 =   \Core\UUID::make(EX_CORE_UUID_TYPE_RANDOM);
        $this->__request_instances[$__request_uuid] =   [
            'request'   =>  $_request,
            'callback'  =>  $_callback_func,
            'status'    =>  EX_HTTP_CLIENT_STATUS_INIT
        ];

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

                    curl_setopt_array($__request_v['request']->get_instance(), $__request_v['request']->get());

                    $__request_v['status']  =   EX_HTTP_CLIENT_STATUS_EXEC;
                    $__return_value[$__request_k]                       =   $this->__get_response($this->__request_instances[$__request_k]['request']->get_instance(), $_multi_request = FALSE, $__request_v['request']->get(EX_NET_HTTP_OPT_RETURNHEADER));

                    $__request_v['status']  =   EX_HTTP_CLIENT_STATUS_RESPONDED;
                }
            }

        }
        else {
            $__instance                 =   curl_multi_init();
            $__active                   =   NULL;
            $__tmp_request_hash         =   [];

            foreach ($this->__request_instances as $__request_k => $__request_v) {
                if ($__request_v['status'] == EX_HTTP_CLIENT_STATUS_INIT) {

                    curl_setopt_array($__request_v['request']->get_instance(), $__request_v['request']->get());
                    curl_multi_add_handle($__instance, $__request_v['request']->get_instance());
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

                $this->__request_instances[$__tmp_hash]['status']       =   EX_HTTP_CLIENT_STATUS_RESPONDED;
                $__return_value[$__tmp_hash]                            =   $this->__get_response($this->__request_instances[$__tmp_hash]['request']->get_instance(), $_multi_request = TRUE);
                curl_multi_remove_handle($__instance, $this->__request_instances[$__tmp_hash]['request']->get_instance());

                /* {{{ EXEC CALLBACK FUNCTION START */
                if (!empty($this->__request_instances[$__tmp_hash]['callback'])){
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
                }
                /* EXEC CALLBACK FUNCTION END }}} */
            }

            curl_multi_close($__instance);
        }


        $this->__response_instances                                     =   $__return_value;

        return $__return_value;
    }

    private function __get_response($_handle, $_multi_request = FALSE, $_return_header = TRUE) {

        $__return_value                     =   [
            'status'        =>  -1,
            'message'       =>  '',
            'info'          =>  [],
            'error_code'    =>  -1,
            'error_message' =>  'No Curl Handle Executed.'
        ];

        $__tmp_response                     =   '';

        if (empty($_handle)) {
            return $__return_value;
        }

        if (!$_multi_request) {
            $__tmp_response                 =   curl_exec($_handle);
        }

        $__error_code                       =   curl_errno($_handle);

        if (0 == $__error_code) {

            $__return_value['info']         =   curl_getinfo($_handle);
            $__return_value['status']       =   $__return_value['info']['http_code'];
            $__return_value['message']      =   (defined('EX_NET_HTTP_CODE_' . $__return_value['status']) ? constant('EX_NET_HTTP_CODE_' . $__return_value['status']) : '');
            if ($_multi_request) {
                $__tmp_response             = curl_multi_getcontent($_handle);
            }

            unset($__return_value['error_code']);
            unset($__return_value['error_message']);
        }
        else {

            $__return_value['error_code']   =   $__error_code;
            $__return_value['error_message']=   curl_error($_handle);

        }

        if ($_return_header) {
            $__return_value                    +=   $this->__parse_response($__tmp_response);
        }
        else {
            $__return_value['response']     =   $__tmp_response;
        }

        return $__return_value;
    }

    private function __parse_response($_response) {

        $__return_value                     =   [
            'header'    =>  [],
            'response'  =>  ''
        ];

        $__tmp_response                     =   explode("\r\n\r\n", $_response, 2);
        $__tmp_header                       =   explode("\r\n",$__tmp_response[0]);
        $__i                                =   0;

        while(isset($__tmp_header[++$__i])) {

            list($__key, $__value)          =   explode(':', $__tmp_header[$__i], 2);
            $__return_value['header'][trim($__key)]   =   trim($__value);
        }

        $__return_value['response']         =   $__tmp_response[1];

        return $__return_value;
    }
}