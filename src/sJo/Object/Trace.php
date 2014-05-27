<?php

namespace sJo\Object;

trait Trace
{
    public static function trace($name = null, $lvl = 0)
    {
        $backtrace = debug_backtrace();

        if (isset($backtrace[$lvl + 1])) {

            if (is_null($name)) {

                return $backtrace;
            }

            if (isset($backtrace[$lvl + 1][$name])) {

                return $backtrace[$lvl + 1][$name];
            }
        }

        return null;
    }
}
