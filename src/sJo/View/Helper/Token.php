<?php

namespace sJo\View\Helper;

use sJo\Loader;
use sJo\Loader\Router;
use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class Token extends Dom
{
    public function setElement($element)
    {
        if (isset($element[static::DEFAULT_WRAPPER])) {
            $element = array(
                'token' => $element[static::DEFAULT_WRAPPER][0]
            );
        }

        $controller = null;
        $method = null;

        if(isset($element['token'])
            && $element['token']) {

            if(preg_match("#^([a-z]+(\\\\([a-z]+))?)(" . Router::$__map['method']['separator'] . "([a-z]+))?$#i", $element['token'], $match)) {
                $controller = $match[1];
                $method = $match[5];
            }

            $element['token'] = Loader\Token::get($element['token']);
        }

        return array(static::DEFAULT_WRAPPER => Lib\Arr::extend(array(
            'token' => null,
            'controller' => $controller,
            'method' => $method
        ), $element));
    }
}
