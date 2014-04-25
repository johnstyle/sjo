<?php

namespace sJo\Object;

trait Singleton
{
    protected static $__instance;

    public static function getInstance($args = null, $hash = null)
    {
        if (!$hash) {
            $hash = get_called_class();
        }

        return isset(static::$__instance[$hash])
            ? static::$__instance[$hash]
            : static::$__instance[$hash] = new static($args);
    }
}
