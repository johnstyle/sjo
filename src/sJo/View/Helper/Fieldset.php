<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;

class Fieldset extends Dom
{
    public function setElement($element)
    {
        $element = parent::setElement($element);

        return array_merge(array(
            'legend' => null,
            'elements' => null
        ), $element);
    }
}
