<?php

/**
 * Created by PhpStorm.
 * User: fate
 * Date: 15/8/18
 * Time: 上午9:09
 */
class Load
{

	static public $registered_class                       =   [];
	static public $private_library                      =   '';
	static private $private_namespace                   =   [];

	static public function register_autoload() {

		spl_autoload_register(function($_classname) {

			if (!isset(\Load::$registered_class[$_classname])) {

				$__tmp_is_private_library               =   FALSE;

				if (!empty(\Load::$private_namespace)) {
					foreach (\Load::$private_namespace as $__namespace) {
						if (0 === strpos($_classname, $__namespace)) {
							$__tmp_is_private_library   =   TRUE;
							break;
						}
					}
				}

				$__tmp_path                             =   ($__tmp_is_private_library ? \Load::$private_library : LIBRARY_1436370634_PATH).
															DIRECTORY_SEPARATOR.
															str_replace('\\', '/', $_classname).
															'.php';
				if (is_file($__tmp_path)) {
					include $__tmp_path;
					\Load::$registered_class[$_classname]=   TRUE;
				}
				elseif (FALSE == $__tmp_is_private_library && is_file(\Load::$private_library . DIRECTORY_SEPARATOR . str_replace('\\', '/', $_classname) . '.php')) {
					include \Load::$private_library.
							DIRECTORY_SEPARATOR.
							str_replace('\\', '/', $_classname).
							'.php';
					\Load::$registered_class[$_classname]=   TRUE;
				}
			}

		});
	}

	static public function import() {

	}
}