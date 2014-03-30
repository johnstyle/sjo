<?php

namespace sJo\Data;

abstract class Validate
{
    public static function is ($type, $var, $length = null, $option = null)
    {
        if ($length && strlen($var) > $length) {
            return false;
        }

        switch ($type) {

            case 'char':
            case 'varchar':
            case 'text':
            case 'string':
                return self::isString($var);
                break;

            case 'int':
            case 'integer':
                return self::isInt($var);
                break;

            case 'bool':
            case 'boolean':
                return self::isBool($var);
                break;

            case 'double':
            case 'float':
                return self::isFloat($var);
                break;

            case 'md5':
                return self::isMd5($var);
                break;

            case 'email':
                return self::isEmail($var);
                break;

            case 'date':
                return self::isDate($var);
                break;

            case 'datetime':
                return self::isDateTime($var);
                break;

            case 'enum':
            case 'set':
                return self::isEnum($var, $option);
                break;

            case 'url':
                return self::isUrl($var);
                break;
        }

        return false;
    }

    public static function isMd5($var)
    {
        return is_scalar($var) && preg_match('#^[a-f0-9]{32}$#', $var);
    }

    public static function isInt($var)
    {
        return is_scalar($var) && preg_match('#^[0-9]+$#', $var);
    }

    public static function isEmail($var)
    {
        return is_scalar($var) && preg_match('#^[a-z0-9\-\.\+]+@[a-z0-9\-]+\.[a-z]{2,4}$#i', $var);
    }

    public static function isUrl($var)
    {
        return is_scalar($var) && preg_match('#^https?://(www\.)?[a-z0-9\.\-]+\.[a-z]{2,4}(/.+|$)#i', $var);
    }

    public static function isDate($var)
    {
        return is_scalar($var) && preg_match('#^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$#', $var);
    }

    public static function isDateTime($var)
    {
        return is_scalar($var) && preg_match('#^[0-9]{4}\-[0-9]{2}\-[0-9]{2}\s[0-9]{2}:[0-9]{2}:[0-9]{2}$#', $var);
    }

    public static function isEnum($var, array $elements = null)
    {
        return is_scalar($var) && $elements && in_array($var, $elements);
    }

    public static function isBool($var)
    {
        return is_scalar($var) && is_bool($var);
    }

    public static function isFloat($var)
    {
        return is_scalar($var) && is_float($var);
    }

    public static function isString($var)
    {
        return is_scalar($var) && is_string($var);
    }

    public static function isEmpty($var)
    {
        return (is_null($var) || $var === '');
    }

    public static function isCallable($var, $namespace = false)
    {
        return is_callable($var) && (!$namespace || (is_scalar($var) && strstr($var, '\\')));
    }
}
