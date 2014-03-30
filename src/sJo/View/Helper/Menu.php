<?php

namespace sJo\View\Helper;

use sJo\Loader\Router;
use sJo\View\Helper\Dom\Dom;
use sJo\View\Helper\Dom\Register;
use sJo\Libraries as Lib;

class Menu extends Dom
{
    use Register;

    public static function setRegistry($element)
    {
        return parent::setElement(Lib\Arr::extend(array(
            'type' => 'navbar'
        ), $element));
    }

    public static function addRegistry($name, $options)
    {
        if (self::isRegistered($name)) {

            $default = array(
                'attributes' => array(
                    'id' => '_' . uniqid(),
                    'class' => null,
                    'title' => null,
                    'target' => null,
                    'data' => array()
                ),
                'icon' => null,
                'tooltip' => null,
                'link' => 'javascript:;',
                'isActive' => false,
                'children' => array(),
            );

            $options = Lib\Arr::extend($default, $options);

            if (count($options['children'])) {

                $options['controller'] = '#' . $options['attributes']['id'];
                $options['attributes']['data'] = array_merge(array(
                    'toggle' => 'collapse',
                    'parent' => '.nav',
                ), $options['attributes']['data']);

                foreach ($options['children'] as &$child) {
                    $childDefault = $default;
                    $childDefault['id'] = '_' . uniqid();
                    $child = Lib\Arr::extend($childDefault, $child);
                }
            }

            self::$registry[$name]['elements'][] = $options;
        }
    }

    public function html(array $options = null)
    {
        if (self::isRegistered($options['method'])) {
            if(count(self::$registry[$options['method']]['elements'])) {
                switch (self::$registry[$options['method']]['type']) {
                    case 'navbar';
                        return $this->inc('nav', Lib\Arr::extend(self::$registry[$options['method']], array(
                            'container' => 'nav'
                        )));
                        break;
                    case 'sidebar';
                        return $this->inc('nav', Lib\Arr::extend(self::$registry[$options['method']], array(
                            'container' => 'aside'
                        )));
                        break;
                }
            }
        }
        return false;
    }
}
