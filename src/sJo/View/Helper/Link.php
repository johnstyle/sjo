<?php

namespace sJo\View\Helper;

use sJo\Loader;
use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class Link extends Dom
{
    protected static $attributes = array(
        'tagname' => 'a',
        'attributes' => array(
            'href' => '#'
        )
    );
}
