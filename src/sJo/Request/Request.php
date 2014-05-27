<?php

namespace sJo\Request;

/**
 * Class Request
 * @package sJo\Request
 *
 * @method static Env env(\string $attr = null, \string $default = null)
 */
abstract class Request
{
    public static function __callStatic($method, $args = null)
    {
        switch($method) {

            case 'env':
                $items = null;
                return new Env($items, $args[0]);
                break;
        }
    }
}
