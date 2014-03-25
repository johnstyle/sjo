<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class Button extends Dom
{
    public function setElement($element)
    {
        $element = parent::setElement(Lib\Arr::extend(array(
            'type' => 'submit'
        ), $element), null, 'value');

        $element['class'] .= ' btn btn-default';

        return $element;
    }
}
