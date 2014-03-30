<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class Input extends Dom
{
    const DEFAULT_WRAPPER = 'attributes';

    protected static $attributes = array(
        'tagname' => 'input',
        'attributes' => array(
            'type' => 'text'
        )
    );

    public function setElement($element)
    {
        $element['attributes']['class'] .= ' form-control';

        return $element;
    }
}
