<?php

namespace sJo\View\Helper\Drivers\Html;

use sJo\Libraries\Arr;
use sJo\View\Helper\Dom;

class Field extends Dom
{
    protected static $element = array(
        'options' => null,
        'attributes' => array(
            'type'        => 'text',
            'placeholder' => null,
            'autofocus'   => null,
            'name'        => null,
            'value'       => null,
        ),
        'label'     => null,
        'group'     => array(
            'class'       => null
        ),
        'alert'     => null,
        'icon'      => null
    );

    public function setElement ($element)
    {
        $attributes = Arr::extend(array(
            'options' => $element['options']
        ), $element['attributes']);

        switch ($element['attributes']['type']) {

            case 'select':
                $element['elements'] = array(self::createStatic('Select', $attributes));
                break;

            case 'textarea':
                $element['elements'] = array(self::createStatic('Textarea', $attributes));
                break;

            default:
                $element['elements'] = array(self::createStatic('Input', $attributes));
                break;
        }

        return $element;
    }
}
