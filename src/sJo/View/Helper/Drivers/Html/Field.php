<?php

namespace sJo\View\Helper\Drivers\Html;

use sJo\View\Helper\Dom;

class Field extends Dom
{
    protected static $element = array(
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
        switch ($element['attributes']['type']) {

            case 'select':
                $element['elements'] = array(self::createStatic('Select', $element['attributes']));
                break;

            case 'textarea':
                $element['elements'] = array(self::createStatic('Textarea', $element['attributes']));
                break;

            default:
                $element['elements'] = array(self::createStatic('Input', $element['attributes']));
                break;
        }

        return $element;
    }
}
