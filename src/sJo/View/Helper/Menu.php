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
        return Lib\Arr::extend(array(
            'type' => 'navbar',
            'items' => null,
            'pull' => null,
            'container' => null,
            'elements' => array()
        ), $element);
    }

    public static function addRegistry($name, $options)
    {
        if (self::isRegistered($name)) {

            if (isset($options['controller'])) {
                $options['controller'] = Router::link($options['controller']);
            }

            $default = array(
                'id' => '_' . uniqid(),
                'icon' => null,
                'class' => null,
                'title' => null,
                'tooltip' => null,
                'controller' => null,
                'link' => null,
                'isActive' => false,
                'children' => array(),
                'data' => array()
            );

            $options = Lib\Arr::extend($default, $options);

            if (count($options['children'])) {

                $options['controller'] = '#' . $options['id'];
                $options['data'] = array_merge(array(
                    'toggle' => 'collapse',
                    'parent' => '.nav',
                ), $options['data']);

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
