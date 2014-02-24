<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class Table extends Dom
{
    public function setElement($element)
    {
        return Lib\Arr::extend(array(
            'id' => null,
            'class' => null,
            'thead' => null,
            'tfoot' => null,
            'tbody' => null
        ), $element);
    }
}
