<?php
/**
 * Created by PhpStorm.
 * User: lovemybud
 * Date: 15/7/14
 * Time: 21:25
 */

namespace Net\Http\Client;


class Request
{

    public function __construct(string $_method = '', string $_url = '', string $_request_body = '', array $_request_headers = [], array $_ssl_options = []) {
        parent::__construct($_method, $_url, $_request_headers);

        if ('' != $_request_body) {
            $this->getBody()->append($_request_body);
        }

        if (!empty($_ssl_options)) {
            $this->setSslOptions($_ssl_options);
        }
    }

    public function set($_key, $_value = NULL) {

        $_return_value                      =   $this;

        $__node                             =   [];

        if (empty($_key)) {
            return $_return_value;
        }
        elseif (is_array($_key)) {
            $__node                         =   $_key;
        }
        else {
            $__node                         =   [$_key => $_value];
        }

        foreach ($__node as $__k => $__v) {

            switch($__k) {

                case 'url':
                    $this->setRequestUrl($__v);
                    break;

                case 'http_version':
                    $this->setHttpVersion($__v);
                    break;

                case 'method':
                    $this->setRequestMethod($__v);
                    break;

                case 'request_body':
                    $this->getBody()->append($__v);
                    break;
                case 'ssl_options':
                    $this->{'_' . $__k}     =   $__v;
                    break;

                case 'request_header':
                    if (is_array($__v)) {
                        $this->setHeaders($__v);
                    }
                    break;

                default:
                    $this->setHeader($__k, $__v);

                    break;
            }
        }

        return $_return_value;
    }

    public function get($_key) {

        $_return_value                      =   NULL;

        switch($_key) {

            case 'url':
                $this->getRequestUrl($_key);
                break;

            case 'http_version':
                $this->getHttpVersion($_key);
                break;

            case 'method':
                $this->getHttpVersion($_key);
                break;

            case 'request_body':
                $this->getBody($_key);
                break;

            case 'request_header':
                $this->getHeaders();
                break;

            default:
                $this->getHeader($_key);
                break;
        }
        return $_return_value;
    }

    public function __set($_key, $_value = NULL) {
        return $this->set($_key, $_value);
    }

    public function __get($_key) {
        return $this->get($_key);
    }
}