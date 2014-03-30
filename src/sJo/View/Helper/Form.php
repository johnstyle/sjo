<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class Form extends Dom
{
    protected static $attributes = array(
        'tagname' => 'form',
        'attributes' => array(
            'method' => 'post'
        )
    );

    public function html(array $options = null)
    {
        return parent::html(Lib\Arr::extend(array(
            'method' => 'Container'
        ), $options));
    }
}
