<?php
/**
 * Created by PhpStorm.
 * User: fate
 * Date: 15/8/7
 * Time: 上午9:36
 */

namespace Net\Restful\Server;


abstract class Handle
{
	private $__serviceholder                =   NULL;
	private $__resource_locate              =   EX_NET_RESTFUL_OPT_RESLOCURL;
	private $__url                          =   '';
	private $__response						=	NULL;

	function __construct($_url = NULL, $_serviceholder = NULL, $_resource_locate = EX_NET_RESTFUL_OPT_RESLOCURL) {
		$this->__serviceholder              =   $_serviceholder;
		$this->__resource_locate            =   $_resource_locate;
		$this->__url                        =   $_url;
		$this->__response					=	new Response();
	}

	public function GET($_service, $_resource, $_parameters, $_properties) {}

	public function DELETE($_service, $_resource, $_parameters, $_properties) {}

	public function HEAD($_service, $_resource, $_parameters, $_properties) {}

	public function TRACE($_service, $_resource, $_parameters, $_properties) {}

	public function OPTIONS($_service, $_resource, $_parameters, $_properties) {}

	public function POST($_service, $_resource, $_parameters, $_properties, $_request_body) {}

	public function PUT($_service, $_resource, $_parameters, $_properties, $_request_body) {}

	public function PATCH($_service, $_resource, $_parameters, $_properties, $_request_body) {}

	public function UPDATE($_service, $_resource, $_parameters, $_properties, $_request_body) {}

	public function get_response() {
		return $this->__response;
	}
}