<?php

namespace sJo\Data;

abstract class Type
{
    public static function set ($type, $var, $length = null)
    {
        switch ($type) {

            case 'char':
            case 'varchar':
            case 'text':
            case 'string':
                $var = self::setString($var);
                break;

            case 'int':
            case 'integer':
                $var = self::setInt($var);
                break;

            case 'bool':
            case 'boolean':
                $var = self::setBool($var);
                break;

            case 'double':
            case 'float':
                $var = self::setFloat($var);
                break;

            case 'md5':
                $var = self::setMd5($var);
                break;

            case 'date':
                $var = self::setDate($var);
                break;

            case 'datetime':
                $var = self::setDateTime($var);
                break;
        }

        if ($length && strlen($var) > $length) {
            $var = substr($var, 0, $length);
        }

        return $var;
    }

    public static function setDate($var)
    {
        $var = trim(preg_replace("#[^0-9\-\s/,\.]#", "", $var), ' -/,.');
        if (preg_match("#^([0-9]{2})[^0-9]([0-9]{2})[^0-9]([0-9]{4})$#", $var, $match)) {
            return $match[3] . '-' .  $match[2] . '-' .  $match[1];
        }

        return null;
    }

    public static function setDateTime($var)
    {
        $var = trim(preg_replace("#[^0-9\-\s/,\.:]#", "", $var), ' -/,.:');
        if (preg_match("#^([0-9]{2})[^0-9]([0-9]{2})[^0-9]([0-9]{4})[^0-9]([0-9]{2})[^0-9]?([0-9]{2})[^0-9]?([0-9]{2})$#", $var, $match)) {
            return $match[3] . '-' .  $match[2] . '-' .  $match[1] . ' ' . $match[4] . ':' . $match[5] . ':' . $match[6];
        }

        return null;
    }

    public static function setMd5($var)
    {
        return $var != '' ? md5($var) : null;
    }

    public static function setInt($var)
    {
        return (int) $var;
    }

    public static function setBool($var)
    {
        return (bool) $var;
    }

    public static function setFloat($var)
    {
        $var = str_replace(',', '.', $var);

        return (float) $var;
    }

    public static function setString($var)
    {
        return (string) $var;
    }
}
