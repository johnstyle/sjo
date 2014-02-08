<?php

namespace sJo\View\Builder;

use sJo\Core;
use sJo\Libraries\I18n;
use sJo\View\Builder\Framework\Framework;

class Menu
{
    private static $registered;
    private static $menu;

    public static function registrer($name, array $options = array())
    {
        self::$registered[$name] = array_merge(array(

        ), $options);
    }

    public static function addItem($name, $item)
    {
        if (self::exists($name)) {
            self::$menu[$name][] = $item;
        }
    }

    public static function display($name)
    {
        if (isset(self::$registered[$name]) && isset(self::$menu[$name])) {
            echo Framework::nav(self::$menu[$name]);
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
