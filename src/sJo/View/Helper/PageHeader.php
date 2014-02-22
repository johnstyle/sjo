<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class PageHeader extends Dom
{
    public function setElement($element)
    {
        $element = parent::setElement($element);

        return Lib\Arr::extend(array(
            'tagname' => 'h1',
            'class' => 'page-header',
            'elements' => array()
        ), $element);
    }

    public function html(array $options = null)
    {
        return parent::html(Lib\Arr::extend($options, array('method' => 'container')));
    }
}
