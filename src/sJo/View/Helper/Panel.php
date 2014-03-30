<?php

namespace sJo\View\Helper;

use sJo\Data\Validate;
use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class Panel extends Dom
{
    const DEFAULT_WRAPPER = 'main';

    protected $wrappers = array(
        self::DEFAULT_WRAPPER,
        'header',
        'footer'
    );

    protected static $attributes = array(
        'tagname' => 'form',
        'attributes' => array(
            'method' => 'post'
        ),
        'header' => null,
        'footer' => null
    );

    public function setElement($element)
    {
        $element['attributes']['class'] .= ' panel panel-default';

        if (!is_null($element['header'])
            && Validate::isString($element['header'])) {

            $element['header'] = Container::create(array(
                'attributes' => array(
                    'class' => 'panel-title'
                ),
                'elements' => $element['header']
            ));
       }

       return $element;
    }
}
