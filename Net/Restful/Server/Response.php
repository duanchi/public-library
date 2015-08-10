<?php
/**
 * Created by PhpStorm.
 * User: fate
 * Date: 15/8/7
 * Time: 下午3:56
 */

namespace Net\Restful\Server;


class Response
{
	private $__http_version                 =   EX_NET_HTTP_VERSION_1_1;
	private $__status                       =   204;
	private $__message                      =   EX_NET_HTTP_CODE_204;
	private $__headers                      =   [];
	private $__properties                   =   [];
	private $__data                         =   NULL;
	private $__content_type                 =   '';

	function __construct () {
	}

	public function set($_key, $_value) {

		switch($_key) {

			case 'http_version':
				$this->__http_version       =   $_value;
				break;

			case 'status':
				$this->__status             =   (defined('EX_NET_HTTP_CODE_' . $_value) ? constant('EX_NET_HTTP_CODE_' . $_value) : '');
				break;

			case 'message':
				$this->__message            =   $_value;
				break;

			case 'data':
				$this->__data               =   $_value;

			case 'content_type':
				$this->__content_type       =   $_value;

			case 'properties':
				if (!is_array($_value)) {
					break;
				}
				else {
					$this->__properties     =   $_value;
				}

			case 'headers':
				$this->__headers            =   (!is_array($_value) ? [$_value] : $_value);
				break;
		}

		return $this;
	}

	public function __set($_key, $_value) {
		return $this->set($_key, $_value);
	}

	public function __get($_key) {
		return $this->{'__' . $_key};
	}
}