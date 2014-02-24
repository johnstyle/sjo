<?php

namespace sJo\Model;

trait Singleton
{
    protected static $__instance;

    public static function getInstance($args = null, $hash = null)
    {
        return isset(static::$__instance[$hash])
            ? static::$__instance[$hash]
            : static::$__instance[$hash] = new static($args);
    }
}
