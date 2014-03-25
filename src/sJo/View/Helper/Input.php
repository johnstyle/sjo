<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class Input extends Dom
{
    public function setElement($element)
    {
        return parent::setElement(Lib\Arr::extend(array(
            'type' => 'text'
        ), $element));
    }
}
