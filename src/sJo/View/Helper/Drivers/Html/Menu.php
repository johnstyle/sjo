<?php

namespace sJo\View\Helper\Drivers\Html;

use sJo\View\Helper\Dom;
use sJo\View\Helper\Register;
use sJo\Libraries as Lib;

class Menu extends Dom
{
    use Register;

    public static function addRegistry($name, $element)
    {
        if (self::isRegistered($name)) {
            self::$registry[$name]->addElements($element);
        }
    }
}
