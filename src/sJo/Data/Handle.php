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

    public static function insertChar($str, $separator = '/', $length = 1, $position = 0)
    {
        return preg_replace(
            '#([^' . $separator . ']{' . $length . '})#',
            (0 === $position ? $separator : '') . '$1' . (1 === $position ? $separator : ''),
            $str
        );
    }
}
