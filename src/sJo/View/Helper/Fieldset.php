<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class Fieldset extends Dom
{
    public function setElement($element)
    {
        $element = parent::setElement($element);

        return Lib\Arr::extend(array(
            'legend' => null,
            'elements' => null
        ), $element);
    }
}
