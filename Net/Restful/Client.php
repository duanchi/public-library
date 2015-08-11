<?php
/**
 * Created by PhpStorm.
 * User: lovemybud
 * Date: 15/7/14
 * Time: 21:25
 */

namespace Net\Restful;


class Client extends \Net\Http\Client
{
	protected function __parse_response($_response)  {

		$__return_value                     =   [
			'headers'       =>  [],
			'properties'    =>  [],
			'response'      =>  ''
		];

		$__tmp_properties                   =   $this->__config['response']['properties'];
		$__tmp_response                     =   explode("\r\n\r\n", $_response, 2);
		$__tmp_header                       =   explode("\r\n",$__tmp_response[0]);
		$__i                                =   0;

		while(isset($__tmp_header[++$__i])) {

			list($__key, $__value)          =   explode(':', $__tmp_header[$__i], 2);
			$__return_value['headers'][trim($__key)]    =   trim($__value);

			$__key                          =   str_replace('-', '_', $__key);

			if (isset($__tmp_properties[$__key])) {
				$__return_value['properties'][$__key]   =   $__value;
				unset($__tmp_properties[$__key]);
			}
		}

		if (!empty($__tmp_properties)) {
			foreach($__tmp_properties as $__value) {
				if (is_array($__value)) {
					$__return_value['properties'][$__value['key']] =   $__value['value'];
				}
				else {
					$__return_value['properties'][$__value]         =   '';
				}
			}
		}

		$__return_value['response']         =   $__tmp_response[1];

		return $__return_value;
	}

	protected function _init_request(\Net\Http\Client\Request $_request) {
		$__config                           =   $this->__config['request'];
		$_request->set(EX_NET_HTTP_OPT_URL, $this->__parse_request_url($_request->get_service(), $_request->get_parameter(), $_request->get_resource(), $__config));
		$_request->set(EX_NET_HTTP_OPT_HEADER, isset($__config['properties']) ? $this->__parse_properties($_request->get_properties(), $__config['properties']) : []);
		return $_request;
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

	private function __parse_request_url($_url, $_parameter, $_resource = NULL, $_config) {

		$__return_value                         =   $_url;
		$_url                                  .=   (empty($_parameter) ? '' : '?' . $_parameter);

		if (!empty($_resource)) {
			$__has_resource_placeholder         =   0;
			$__return_value                     =   str_replace($_config['resource']['placeholder'], $_resource, $_url, $__has_resource_placeholder);

			if (0 === $__has_resource_placeholder) {
				$__return_value                 =   (empty($_parameter) ? rtrim($_url, '/') . '/' : $_url . '&') . $_resource;
			}
		}

		return $__return_value;
	}
}