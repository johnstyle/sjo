<?php

namespace PHPTools;

class Request
{
    public function getToken($method)
    {
        return md5(session_id() . PHPTOOLS_SALT . $method);
    }

    public function hasToken()
    {
        if(Libraries\Env::request('token') == $this->getToken(CONTROLLER . '/' . METHOD)) {
            return true;
        }
        return false;
    }

    public function filter($key)
    {
        return Libraries\Arr::getTree(Libraries\Env::get('filters'), $key);
    }
}
