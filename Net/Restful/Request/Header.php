<?php

/**
 * Created by PhpStorm.
 * User: lovemybud
 * Date: 15/7/14
 * Time: 23:14
 */
namespace Net\Restful\Request;
class Header
{
    protected $_host    =   '';
    protected $_accept  =   '';
    protected $_accept_encoding =   '';
    protected $_accept_charset  =   '';
    protected $_user_agent      =   '';
    protected $_access_token    =   '';
    protected $_client_id       =   '';
    protected $_version         =   '';
    protected $_ranges          =   '';
    protected $_header_list     =   [];

    function __construct() {

    }
}