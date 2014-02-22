<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class Button extends Dom
{
    public function setElement($element)
    {
        if (!is_array($element)) {
            $element = array('value' => $element);
        }

        return Lib\Arr::extend(array(
            'type' => 'submit',
            'class' => null,
            'name' => null,
            'value' => null
        ), $element);
    }
}
