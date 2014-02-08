<?php

namespace sJo\View\Helper;

use sJo\Core;
use sJo\Libraries\I18n;
use sJo\View\Helper\Framework\Framework;

class Menu
{
    private static $registered;
    private static $menu;

    public static function registrer($name, array $options = array())
    {
        self::$registered[$name] = array_merge(array(
            'type' => 'navbar'
        ), $options);
    }

    public static function addItem($name, $options)
    {
        if (self::exists($name)) {
            self::$menu[$name][] = array_merge(array(
                'icon' => null,
                'title' => null,
                'tooltip' => null,
                'controller' => null,
                'isActive' => false
            ), $options);
        }
    }

    public static function display($name)
    {
        if (isset(self::$registered[$name])
            && isset(self::$menu[$name])) {
            switch(self::$registered[$name]['type']) {
                case 'navbar';
                    echo Framework::nav(self::$menu[$name]);
                    break;
                case 'sidebar';
                    echo Framework::aside(self::$menu[$name]);
                    break;
            }
        }
    }

    private static function exists($name)
    {
        if (isset(self::$registered[$name])) {
            return true;
        } else {
            Core\Exception::error(I18n::__('Menu %s is nor registered.', $name));
        }
        return false;
    }
}
