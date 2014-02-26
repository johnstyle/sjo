<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class ButtonGroup extends Dom
{
    public function setElement($element)
    {
        $element = parent::setElement($element);

        return Lib\Arr::extend(array(
            'class' => null,
            'elements' => null
        ), $element);
    }
}
