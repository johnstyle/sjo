<?php

namespace sJo\View\Helper;

use sJo\Loader;
use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class Link extends Dom
{
    public function setElement($element)
    {
        if (!is_array($element)) {
            $element = array('value' => $element);
        }

        return Lib\Arr::extend(array(
            'href' => '#',
            'target' => null,
            'class' => null,
            'id' => null,
            'value' => null
        ), $element);
    }
}
