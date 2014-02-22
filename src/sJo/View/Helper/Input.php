<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class Input extends Dom
{
    public function setElement($element)
    {
        return Lib\Arr::extend(array(
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
