<?php

namespace sJo\View\Helper;

use sJo\Core;
use sJo\View\Helper\Dom\Dom;

class Input extends Dom
{
    public function setElement($element)
    {
        return array_merge(array(
            'type' => 'text',
            'name' => null,
            'class' => null,
            'placeholder' => null,
            'id' => null,
            'label' => null,
            'value' => null,
            'autofocus' => null
        ), $element);
    }
}
