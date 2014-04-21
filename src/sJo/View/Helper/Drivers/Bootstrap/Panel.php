<?php

namespace sJo\View\Helper\Drivers\Bootstrap;

use sJo\Data\Validate;
use sJo\View\Helper\Dom;

class Panel extends Dom
{
    const DEFAULT_ELEMENT_ATTRIBUTE_CLASS = 'panel panel-default';

    protected $wrapper = 'main';
    protected $wrappers = array(
        'main',
        'header',
        'footer'
    );

    protected static $element = array(
        'tagname' => 'form',
        'attributes' => array(
            'enctype' => 'multipart/form-data',
            'method' => 'post',
            'action' => null,
            'target' => null,
        ),
        'header' => null,
        'footer' => null
    );

    public function setElement($element)
    {
        if (!is_null($element['header'])
            && Validate::isString($element['header'])) {

            $element['header'] = Container::create(array(
                'class' => 'panel-title',
                'elements' => $element['header']
            ));
       }

       return $element;
    }
}
