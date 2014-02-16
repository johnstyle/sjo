<?php

namespace sJo\View\Helper;

use sJo\Core;
use sJo\Libraries\I18n;
use sJo\View\Helper\Dom\Dom;

class Menu extends Dom
{
    private static $registered;
    private static $items;
    private $current;

    public function __construct($menu, array $args = array())
    {
        $this->current = $menu;
    }

    public static function __callStatic($method, array $args = array())
    {
        return new self($method);
    }

    public function register(array $options = array())
    {
        self::$registered[$this->current] = array_merge(
            array(
                'type' => 'navbar',
                'items' => null,
                'pull' => null,
                'container' => null
            ),
            $options
        );
    }

    public function addItem($options)
    {
        if (self::isRegistered()) {
            self::$items[$this->current][] = array_merge(
                array(
                    'icon' => null,
                    'title' => null,
                    'tooltip' => null,
                    'controller' => null,
                    'link' => null,
                    'isActive' => false
                ),
                $options
            );
        }
    }

    private function isRegistered()
    {
        if (isset(self::$registered[$this->current])) {
            return true;
        } else {
            Core\Exception::error(I18n::__('Menu %s is nor registered.', $this->current));
        }
        return false;
    }

    public function html()
    {
        if (self::isRegistered()
            && self::hasItems()
        ) {
            switch ($this->getRegistered()['type']) {
                case 'navbar';
                    return $this->inc('nav', array_merge($this->getRegistered(), array(
                        'items' => $this->getItems(),
                        'container' => 'nav'
                    )));
                    break;
                case 'sidebar';
                    return $this->inc('nav', array_merge($this->getRegistered(), array(
                        'items' => $this->getItems(),
                        'container' => 'aside'
                    )));
                    break;
            }
        }
    }

    public function hasItems()
    {
        if (isset(self::$items[$this->current])) {
            return true;
        }
        return false;
    }

    private function getRegistered()
    {
        if ($this->isRegistered()) {
            return self::$registered[$this->current];
        }
        return array();
    }

    private function getItems()
    {
        if ($this->hasItems()) {
            return self::$items[$this->current];
        }
        return array();
    }
}
