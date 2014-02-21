<?php

namespace sJo\View\Helper;

use sJo\View\Helper\Dom\Dom;

class Panel extends Dom
{
    public function setElement($element)
    {
        $element = parent::setElement($element);

        return array_merge(array(
            'col' => null,
            'type' => 'default',
            'container' => array(
                'tagname' => 'form',
                'attr' => array(
                    'method' => 'post'
                )
            ),
            'title' => null,
            'elements' => null,
            'footer' => null
        ), $element);
    }

    public function display()
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
                    'elements' => parent::html(function ($element) {
                        if(!$element['col']) {
                            $element['col'] = 12;
                        }
                        return $element;
                    })
                ))->display();
            } else {
                parent::display();
            }
        }
    }
}
