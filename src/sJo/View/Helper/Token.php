<?php

namespace sJo\View\Helper;

use sJo\Core;
use sJo\View\Helper\Dom\Dom;

class Token extends Dom
{
    public function element($element)
    {
        if (isset($element['__default__'])) {
            $element = array(
                'token' => $element['__default__']
            );
        }

        $controller = null;
        $method = null;

        if(isset($element['token'])
            && $element['token']) {

            if(preg_match("#^([a-z]+(/([a-z]+))?)(::([a-z]+))?$#i", $element['token'], $match)) {
                $controller = $match[1];
                $method = $match[5];
            }

            $element['token'] = Core\Request::getInstance()->getToken($element['token']);
        }

        return array('elements' => array_merge(array(
            'token' => null,
            'controller' => $controller,
            'method' => $method
        ), $element));
    }
}
