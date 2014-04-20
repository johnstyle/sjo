<?php

namespace sJo\View\Helper\Drivers\Html;

use sJo\View\Helper\Dom;
use sJo\Libraries as Lib;

class Form extends Dom
{
    protected static $element = array(
        'tagname' => 'form',
        'attributes' => array(
            'method' => 'post',
            'action' => null,
            'target' => null,
        )
    );
}
