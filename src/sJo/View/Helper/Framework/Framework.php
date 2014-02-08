<?php

namespace sJo\View\Helper\Framework;

class Framework
{
    private static $view;
    private static $frameworkName = 'Bootstrap';

    public static function set($frameworkName = null)
    {
        if($frameworkName) {
            self::$frameworkName = $frameworkName;
        }
    }

    public static function nav($items)
    {
        return self::inc('nav.php', array(
            'items' => $items,
            'container' => 'nav',
            'pull' => 'right'
        ));
    }

    public static function aside($items)
    {
        return self::inc('nav.php', array(
            'items' => $items,
            'container' => 'aside'
        ));
    }

    private static function inc()
    {
        $args = func_get_args();
        $file = array_shift($args);

        self::$view = new \stdClass();

        foreach($args as $arg) {
            foreach($arg as $name=>$value) {
                self::$view->{$name} = $value;
            }
        }

        return include self::$frameworkName . '/' . $file;
    }
}
