<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;

class Form extends Dom
{
    public function setElement($element)
    {
        $element = parent::setElement($element);

        return array_merge(array(
            'action' => null,
            'method' => 'post',
            'class' => null
        ), $element);
    }
}
