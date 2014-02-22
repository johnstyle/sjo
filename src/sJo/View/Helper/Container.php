<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;

class Container extends Dom
{
    public function setElement($element)
    {
        $element = parent::setElement($element);

        return array_merge(array(
            'tagname' => 'div',
            'class' => null,
            'elements' => null
        ), $element);
    }
}
