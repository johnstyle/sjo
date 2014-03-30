<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;

class Grid extends Dom
{
    protected static $attributes = array(
        'tagname' => 'div'
    );

    public function setElement($element)
    {
        $element['attributes']['class'] .= ' row';

        return $element;
    }
}
