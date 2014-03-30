<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class ButtonGroup extends Dom
{
    public function html(array $options = null)
    {
        return parent::html(Lib\Arr::extend(array(
            'method' => 'Container'
        ), $options));
    }
}
