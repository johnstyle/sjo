<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;
use sJo\View\Helper\Dom\Register;

class Menu extends Dom
{
    use Register;

    public static function setRegistry($element)
    {
        return array_merge(
            array(
                'type' => 'navbar',
                'items' => null,
                'pull' => null,
                'container' => null,
                'elements' => array()
            ),
            $element
        );
    }

    public static function addRegistry($name, $options)
    {
        if (self::isRegistered($name)) {
            self::$registry[$name]['elements'][] = array_merge(
                array(
                    'icon' => null,
                    'title' => null,
                    'tooltip' => null,
                    'controller' => null,
                    'link' => null,
                    'isActive' => false
                ),
                $options
            );
        }
    }

    public function html($name = null)
    {
        if (self::isRegistered($name)) {
            if(count(self::$registry[$name]['elements'])) {
                switch (self::$registry[$name]['type']) {
                    case 'navbar';
                        return $this->inc('nav', array_merge(self::$registry[$name], array(
                            'container' => 'nav'
                        )));
                        break;
                    case 'sidebar';
                        return $this->inc('nav', array_merge(self::$registry[$name], array(
                            'container' => 'aside'
                        )));
                        break;
                }
            }
        }
        return false;
    }
}
