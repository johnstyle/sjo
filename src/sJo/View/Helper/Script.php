<?php

namespace sJo\View\Helper;

use sJo\Loader\Router;
use sJo\Libraries as Lib;
use sJo\View\Helper\Dom\Dom;
use sJo\View\Helper\Dom\Register;

class Script extends Dom
{
    use Register;

    public function setElement($element)
    {
        foreach ($element as &$el) {
            if (!is_array($el)) {
                $el = array('link' => $el);
            }
        }

        return array('elements' => $element);
    }

    public static function create($registered)
    {
        $cssPath = SJO_ROOT_PUBLIC_HTML . '/js/' . strtolower(Router::$interface);
        if (is_dir($cssPath)) {
            $files = Lib\Path::listFiles($cssPath);
            if($files) {
                foreach($files as $file) {
                    $registered[] = preg_replace("#^" . SJO_ROOT_PUBLIC_HTML . "/#", "", $file->path);
                }
            }
        }

        return parent::create($registered);
    }
}