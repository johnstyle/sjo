<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class PageHeader extends Dom
{
    protected static $attributes = array(
        'tagname' => 'h1',
        'attributes' => array(
            'class' => 'page-header'
        )
    );

    public function html(array $options = null)
    {
        return parent::html(Lib\Arr::extend(array(
            'method' => 'Container'
        ), $options));
    }
}
