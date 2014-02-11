<?php

namespace sJo\View\Helper;

use sJo\Core;
use sJo\View\Helper\Dom\Dom;

class Fieldset extends Dom
{
    public function element($element)
    {
        $element = parent::element($element);

        return array_merge(array(
            'legend' => null,
            'elements' => null
        ), $element);
    }
}
