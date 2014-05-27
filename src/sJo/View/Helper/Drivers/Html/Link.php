<?php

namespace sJo\View\Helper\Drivers\Html;

use sJo\View\Helper\Dom;

class Link extends Dom
{
    protected $wrappers = array(
        'elements',
        'subelements'
    );

    protected static $element = array(
        'tagname' => 'a',
        'icon' => null,
        'attributes' => array(
            'href'   => '#',
            'target' => null,
        )
    );

    public function setElement($element)
    {
        if (!isset($element['elements'])
            && $element['attributes']['title']) {
            $element['elements'] = $element['attributes']['title'];
            unset($element['attributes']['title']);
        }

        return $element;
    }
}
