<?php

/**
 * Created by PhpStorm.
 * User: fate
 * Date: 15/8/18
 * Time: 上午9:09
 */
class Load
{

	static private $__registered_class                     =   [];
	static private $__private_library                      =   '';
	static private $__private_namespace                    =   [];

	static public function register_autoload($_private_library, $_private_namespace) {

		self::$__private_library                          =   $_private_library;
		self::$__private_namespace                        =   explode($_private_namespace);

		spl_autoload_register(function($_classname) {

			if (!isset(\Load::$__registered_class[$_classname])) {

				$__tmp_is_private_library               =   FALSE;

				if (!empty(\Load::$__private_namespace)) {
					foreach (\Load::$__private_namespace as $__namespace) {
						if (0 === strpos($_classname, $__namespace)) {
							$__tmp_is_private_library   =   TRUE;
							break;
						}
					}
				}

				$__tmp_path                             =   ($__tmp_is_private_library ? \Load::$__private_library : LIBRARY_1436370634_PATH).
															DIRECTORY_SEPARATOR.
															str_replace('\\', '/', $_classname).
															'.php';
				if (is_file($__tmp_path)) {
					include $__tmp_path;
					\Load::$__registered_class[$_classname]=   TRUE;
				}
				elseif (FALSE == $__tmp_is_private_library && is_file(\Load::$__private_library . DIRECTORY_SEPARATOR . str_replace('\\', '/', $_classname) . '.php')) {
					include \Load::$__private_library.
							DIRECTORY_SEPARATOR.
							str_replace('\\', '/', $_classname).
							'.php';
					\Load::$__registered_class[$_classname]=   TRUE;
				}
			}

		});
	}

	static public function import() {

	}
}