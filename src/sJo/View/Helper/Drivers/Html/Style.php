<?php

namespace sJo\View\Helper\Drivers\Html;

use sJo\Loader\Router;
use sJo\Libraries as Lib;
use sJo\View\Helper\Dom;
use sJo\View\Helper\Register;

class Style extends Dom
{
    use Register;

    protected static $element = array(
        'attributes' => array(
            'href' => null,
            'rel' => 'stylesheet',
            'type' => 'text/css'
        )
    );

    public static function create($registered)
    {
        $cssPath = SJO_ROOT_PUBLIC_HTML . '/css/' . strtolower(Router::$interface);
        if (is_dir($cssPath)) {
            if($files = Lib\Path::listFiles($cssPath)) {
                foreach($files as $file) {
                    array_push($registered['elements'], preg_replace("#^" . SJO_ROOT_PUBLIC_HTML . "/#", "", $file->path));
                }
            }
        }

        foreach ($registered['elements'] as &$el) {
            $el = self::createStatic('Element', Lib\Arr::extend(self::$element, array(
                'tagname' => 'link',
                'attributes' => array(
                    'href' => $el
                )
            )));
        }

        return parent::create($registered);
    }
}
