<?php

namespace sJo\View\Framework;

class Framework
{
    private static $view;

    public static function nav($items)
    {
        self::$view = (object) array(
            'items' => $items
        );

        return include 'View/nav.php' ;
    }
}
