<?php

namespace sJo\View\Helper;

use sJo\Core;
use sJo\Libraries as Lib;

class Style
{
    private static $registered = array();

    public static function register(array $items = array())
    {
        self::$registered = array_merge(self::$registered, $items);
    }

    public static function display()
    {
        $cssPath = SJO_ROOT_PUBLIC_HTML . '/css/' . strtolower(Core\Loader::$interface);
        if (is_dir($cssPath)) {
            $files = Lib\Path::listFiles($cssPath);
            if($files) {
                foreach($files as $file) {
                    self::$registered[] = preg_replace("#^" . SJO_ROOT_PUBLIC_HTML . "/#", "", $file->path);
                }
            }
        }

        if (count(self::$registered)) {
            foreach(self::$registered as $link) {
                echo '<link rel="stylesheet" href="' . $link . '" type="text/css">';
            }
        }
    }
}
