<?php

namespace sJo\View\Helper;

use sJo\Core;
use sJo\View\Helper\Dom\Dom;

class Button extends Dom
{
    public function setElement($element)
    {
        if (!is_array($element)) {
            $element = array('value' => $element);
        }

        return array_merge(array(
            'type' => 'submit',
            'class' => null,
            'name' => null,
            'value' => null
        ), $element);
    }
}
