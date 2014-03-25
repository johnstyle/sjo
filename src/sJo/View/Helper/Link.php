<?php

namespace sJo\View\Helper;

use sJo\Loader;
use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class Link extends Dom
{
    public function setElement($element)
    {
        return parent::setElement(Lib\Arr::extend(array(
            'href' => '#'
        ), $element));
    }
}
