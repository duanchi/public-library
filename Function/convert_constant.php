<?php
/**
 * Created by PhpStorm.
 * User: fate
 * Date: 15/7/17
 * Time: 下午3:48
 */

function convert_constant($_scope, $_input) {
	$_return_value                      =   '';

	switch($_scope) {
		case 'EX_NET_HTTP':
		case 'EX_NET_RESTFUL':
			switch($_input) {
				case EX_NET_HTTP_METHOD_GET:
					$_return_value                  =   'GET';
					break;
				case EX_NET_HTTP_METHOD_DELETE:
					$_return_value                  =   'DELETE';
					break;
				case EX_NET_HTTP_METHOD_HEAD:
					$_return_value                  =   'HEAD';
					break;
				case EX_NET_HTTP_METHOD_TRACE:
					$_return_value                  =   'TRACE';
					break;
				case EX_NET_HTTP_METHOD_OPTIONS:
					$_return_value                  =   'OPTINS';
					break;
				case EX_NET_HTTP_METHOD_POST:
					$_return_value                  =   'POST';
					break;
				case EX_NET_HTTP_METHOD_PUT:
					$_return_value                  =   'PUT';
					break;
				case EX_NET_HTTP_METHOD_PATCH:
					$_return_value                  =   'PATCH';
					break;
				case EX_NET_HTTP_METHOD_UPDATE:
					$_return_value                  =   'UPDATE';
					break;
			}
			break;

		case 'EX_NET_HTTP_VERSION':
			switch($_input) {
				case EX_NET_HTTP_VERSION_1_0:
					$_return_value = 'HTTP/1.0';
					break;
				case EX_NET_HTTP_VERSION_1_1:
					$_return_value = 'HTTP/1.1';
					break;
				case EX_NET_HTTP_VERSION_2_0:
					$_return_value = 'HTTP/2.0';
					break;
			}
	}

	return $_return_value;
}