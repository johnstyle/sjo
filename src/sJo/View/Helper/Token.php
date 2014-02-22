<?php

namespace sJo\View\Helper;

use sJo\Controller\Component\Component;
use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class Token extends Dom
{
    public function setElement($element)
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

            $element['token'] = Component::getInstance()->request->getToken($element['token']);
        }

        return array('elements' => Lib\Arr::extend(array(
            'token' => null,
            'controller' => $controller,
            'method' => $method
        ), $element));
    }
}
