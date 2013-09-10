<?php

namespace PHPTools;

class Request
{
    public function getToken($method)
    {
        Session::start();

        return md5(session_id() . PHPTOOLS_SALT . $method);
    }

    public function hasToken()
    {
        $controller = str_replace('\\', '/', CONTROLLER);
        if(Libraries\Env::request('token') == $this->getToken($controller . PHPTOOLS_CONTROLLER_METHOD_SEPARATOR . METHOD)) {
            return true;
        }
        return false;
    }

    public function filter($key)
    {
        return Libraries\Arr::getTree(Libraries\Env::get('filters'), $key);
    }
}
