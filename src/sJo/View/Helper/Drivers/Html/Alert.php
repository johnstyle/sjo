<?php

namespace sJo\View\Helper\Drivers\Html;

use sJo\View\Helper\Dom;

class Alert extends Dom
{
    protected static $element = array(
        'formHash' => null
    );

    protected function setElement ($element)
    {
        if (isset($element['elements']['formHash'])) {

            $element['formHash'] = $element['elements']['formHash'];
        }

        return $element;
    }
}
