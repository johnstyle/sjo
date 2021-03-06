<?php

namespace sJo\View\Helper;

use sJo\Exception\Exception;
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

                self::$registry[$name] = $element;

            } else {

                if (!isset(self::$registry[null])) {

                    self::$registry[null] = array();
                }

                self::$registry[null]['elements'][] = $element;
            }
        }
    }

    public static function hasRegistry($name = null)
    {
        if (self::isRegistered($name, false)) {

            return true;
        }

        return false;
    }

    private static function isRegistered($name = null, $exception = true)
    {
        if (isset(self::$registry[$name])) {
            return true;
        } elseif($exception) {
            Exception::error(I18n::__('%s element %s is nor registered.', __CLASS__, $name));
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

            $options = null;
            if ($name) {
                $options = array('method' => $name);
            }

            self::create(self::$registry[$name])->render($options);
        }
    }
}
