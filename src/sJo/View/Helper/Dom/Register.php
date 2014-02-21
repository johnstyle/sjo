<?php

namespace sJo\View\Helper\Dom;

use sJo\Core;
use sJo\Libraries\I18n;

trait Register
{
    private static $registry = array();

    public static function register(array $elements)
    {
        if (!isset(self::$registry)) {
            self::$registry = array();
        }
        foreach ($elements as $name => $element) {
            if (is_string($name)) {
                if (!isset(self::$registry[$name])) {
                    self::$registry[$name] = array();
                }
                self::$registry[$name] = static::setRegistry($element);
            } else {
                self::$registry[null][] = static::setRegistry($element);
            }
        }
    }

    private static function setRegistry($element)
    {
        return $element;
    }

    public static function hasRegistry($name)
    {
        if (self::isRegistered($name)) {
            return count(self::$registry[$name]['elements']);
        }
    }

    private static function isRegistered($name = null)
    {
        if (isset(self::$registry[$name])) {
            return true;
        } else {
            Core\Exception::error(I18n::__('%s element %s is nor registered.', __CLASS__, $name));
        }
        return false;
    }

    public static function addRegistry($name, array $options = array())
    {
        if (self::isRegistered($name)) {
            self::$registry[$name]['elements'][] = $options;
        }
    }

    public static function applyRegistry($name = null)
    {
        if (self::isRegistered($name)) {
            self::create(self::$registry[$name])->display($name);
        }
    }
}
