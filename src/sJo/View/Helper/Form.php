<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class Form extends Dom
{
    public function setElement($element)
    {
        $element = parent::setElement($element);

        return Lib\Arr::extend(array(
            'action' => null,
            'method' => 'post',
            'class' => null
        ), $element);
    }
}
