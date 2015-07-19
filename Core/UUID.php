<?php
/**
 * Created by PhpStorm.
 * User: lovemybud
 * Date: 15/7/19
 * Time: 22:56
 */

namespace Core;


class UUID
{
    static public function make($_type = EX_CORE_UUID_TYPE_DEFAULT) {
        return uuid_create($_type);
    }
}