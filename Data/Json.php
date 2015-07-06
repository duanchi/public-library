<?php
/**
 * Created by PhpStorm.
 * User: fate
 * Date: 15/4/9
 * Time: 上午9:39
 */

namespace Data;


class Json extends DataAbstract {

	static protected $__DATA_TYPE       =   'json';
	static protected $__SUFFIX          =   '.json';

	static public function t() {
		t(parent::$__DATA_TYPE  =   self::$__DATA_TYPE);
	}

	static protected function decode_data($_data) {
		return json_decode($_data, TRUE);
	}

	static protected function encode_data($_data) {
		return json_encode($_data, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
	}
}