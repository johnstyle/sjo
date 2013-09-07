<?php

namespace PHPTools;

class Request
{
    private static $id;

    public function __construct($controller = 'controller', $method = 'method')
    {
        defined('CONTROLLER') OR define('CONTROLLER', Env::request($controller, 'Home'));
        defined('METHOD') OR define('METHOD', Env::request($method));

        self::$id = md5(CONTROLLER . '/' . METHOD);
    }

    public static function getToken($method = false)
    {
        if($method) {
            self::$id = md5($method);
        }
        return self::$id;
    }

    public static function hasToken()
    {
        if(Env::request('token') == self::$id) {
            return true;
        }
        return false;
    }

    public static function filter($key)
    {
        return Arr::getTree(Env::get('filters'), $key);
    }
}
