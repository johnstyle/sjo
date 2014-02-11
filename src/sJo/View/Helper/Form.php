<?php

namespace sJo\View\Helper;

use sJo\Core;
use sJo\View\Helper\Dom\Dom;

class Form extends Dom
{
    public function element($element)
    {
        $element = parent::element($element);

        return array_merge(array(
            'action' => null,
            'method' => 'post',
            'class' => null
        ), $element);
    }
}
