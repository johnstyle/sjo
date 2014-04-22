<?php

namespace sJo\View\Helper\Drivers\Html;

use sJo\Loader\Router;
use sJo\Libraries as Lib;
use sJo\View\Helper\Dom;
use sJo\View\Helper\Register;
use sJo\File\Path;

class Script extends Dom
{
    use Register;

    protected static $element = array(
        'attributes' => array(
            'src' => null,
            'type' => 'text/javascript'
        )
    );

    public static function create($registered)
    {
        $cssPath = SJO_ROOT_PUBLIC_HTML . '/js/' . strtolower(Router::$interface);
        if (is_dir($cssPath)) {
            if($files = Path::listFiles($cssPath)) {
                foreach($files as $file) {
                    $registered['elements'][] = preg_replace("#^" . SJO_ROOT_PUBLIC_HTML . "/#", "", $file->path);
                }
            }
        }

        foreach ($registered['elements'] as &$el) {
            $el = self::createStatic('Element', Lib\Arr::extend(self::$element, array(
                'tagname' => 'script',
                'attributes' => array(
                    'src' => $el
                )
            )));
        }

        return parent::create($registered);
    }
}