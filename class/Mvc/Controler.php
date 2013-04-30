<?php

namespace PHPTools\Mvc;

class Controler
{
    public function __construct($controler = 'controler')
    {
        if (!defined('CONTROLER')) {
            define('CONTROLER', \PHPTools\Env::get($controler, 'Home'));
        }
        if (CONTROLER) {
            $className = 'Core\\' . CONTROLER;
            if (class_exists($className) && method_exists($className, '__load')) {
                $class = new $className();
                $class->__load();
                View::setObject(CONTROLER, $class);
                View::inc(CONTROLER);
            }
        }
    }
}
