<?php

namespace sJo\View\Helper\Drivers\Html;

use sJo\View\Helper\Dom;

class Button extends Dom
{
    protected static $element = array(
        'tagname' => 'button',
        'attributes' => array(
            'type'  => 'submit',
            'name'  => null
        )
    );

    public function setElement($element)
    {
        if (isset($element['link'])) {
            $element['tagname'] = 'a';
            $element['href'] = $element['link'];
            unset($element['link']);
            unset($element['attributes']['type']);
        }

        if (!isset($element['elements'])
            && isset($element['value'])) {
            $element['elements'] = $element['value'];
            unset($element['value']);
        }

        return $element;
    }
}
