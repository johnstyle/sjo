<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class Container extends Dom
{
    public function setElement($element)
    {
        $element = parent::setElement($element);

        return Lib\Arr::extend(array(
            'tagname' => 'div',
            'class' => null,
            'elements' => null
        ), $element);
    }
}
