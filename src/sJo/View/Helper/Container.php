<?php

namespace sJo\View\Helper;

use sJo\Core;
use sJo\View\Helper\Dom\Dom;

class Container extends Dom
{
    public function element($element)
    {
        $element = parent::element($element);

        return array_merge(array(
            'tagname' => 'div',
            'class' => null,
            'elements' => null
        ), $element);
    }
}
