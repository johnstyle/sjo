<?php

namespace sJo\View\Helper\Drivers\Html;

use sJo\View\Helper\Dom;

class Form extends Dom
{
    protected static $element = array(
        'tagname' => 'form',
        'attributes' => array(
            'enctype' => 'multipart/form-data',
            'method' => 'post',
            'action' => null,
            'target' => null,
        )
    );
}
