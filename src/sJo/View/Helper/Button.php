<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class Button extends Dom
{
    const DEFAULT_WRAPPER = 'attributes';

    protected static $attributes = array(
        'tagname' => 'button',
        'attributes' => array(
            'type' => 'submit'
        )
    );

    public function setElement($element)
    {
        $element['attributes']['class'] .= ' btn btn-default';

        if (isset($element['link'])) {
            $element['tagname'] = 'a';
            $element['href'] = $element['link'];
            unset($element['link']);
            unset($element['attributes']['type']);
        }

        if (!isset($element['elements'])
            && $element['attributes']['value']) {
            $element['elements'] = $element['attributes']['value'];
            unset($element['attributes']['value']);
        }

        return $element;
    }

    public function html(array $options = null)
    {
        return parent::html(Lib\Arr::extend(array(
            'method' => 'Container'
        ), $options));
    }
}
