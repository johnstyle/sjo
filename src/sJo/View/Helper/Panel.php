<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class Panel extends Dom
{
    public function setElement($element)
    {
        return parent::setElement(Lib\Arr::extend(array(
            'col' => null,
            'container' => array(
                'tagname' => 'form',
                'attr' => array(
                    'method' => 'post'
                )
            ),
            'footer' => null
        ), $element), 'elements');
    }

    public function display(array $options = null)
    {
        if ($this->elements) {
            $container = false;
            foreach($this->elements as $element) {
                if($element['col']) {
                    $container = true;
                    break;
                }
            }
            if ($container) {
                Container::create(array(
                    'class' => 'row',
                    'elements' => parent::html(array(
                        'callback' => function ($element) {
                            if(!$element['col']) {
                                $element['col'] = 12;
                            }
                            return $element;
                        })
                    )
                ))->display();
            } else {
                parent::display();
            }
        }
    }
}
