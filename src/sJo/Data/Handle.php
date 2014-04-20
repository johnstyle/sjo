<?php

namespace sJo\Data;

abstract class Handle
{
    public static function cutLast($var, $pattern)
    {
        return substr($var, strrpos($var, $pattern)+1, strlen($var));
    }

    public static function cutFirst($var, $pattern)
    {
        return substr($var, 0, strpos($var, $pattern));
    }
}
