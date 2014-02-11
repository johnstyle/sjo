<?php

namespace sJo\View\Helper\Dom;

use sJo\Core;

abstract class Dom
{
    private static $view;
    private static $frameworkName = 'Bootstrap';
    protected $elements;

    public function __construct($elements)
    {
        $this->elements = $elements;

        foreach ($this->elements as &$element) {
            if (!is_array($element)) {
                $element = array('__default__' => $element);
            }
        }

        $this->elements();
    }

    protected function elements()
    {
        foreach ($this->elements as &$element) {
            $element = $this->element($element);
        }
    }

    protected function element($element)
    {
        if(!isset($element['elements'])) {
            $element = array('elements' => $element);
        }

        if (!is_array($element['elements'])) {
            $element['elements'] = array($element['elements']);
        }

        return $element;
    }

    /**
     * @return Dom
     */
    public static function create()
    {
        $class = get_called_class();
        return new $class(func_get_args());
    }

    public function display(callable $callback = null)
    {
        echo $this->html($callback);
    }

    public function html(callable $callback = null)
    {
        $class = new \ReflectionClass(get_called_class());
        $method = strtolower($class->getShortName());
        $method = str_replace('dom', '', $method);

        $html = '';

        if ($this->elements) {
            foreach ($this->elements as $element) {
                $html .= $this->inc($method, $this->buildElements($element));
            }
        }

        return $html;
    }

    protected function inc()
    {
        $args = func_get_args();
        $file = array_shift($args);

        self::$view = new \stdClass();

        if (count($args)) {
            foreach ($args as $arg) {
                if (is_array($arg)) {
                    foreach ($arg as $name => $value) {
                        self::$view->{$name} = $value;
                    }
                } else {
                    if (!isset(self::$view->elements)) {
                        self::$view->elements = array();
                    }
                    self::$view->elements[] = $arg;
                }
            }
        }

        ob_start();
        include(self::$frameworkName . '/' . $file . '.php');
        return ob_get_clean();
    }

    private function buildElements($elements)
    {
        foreach ($elements as &$element) {
            if (is_callable($element)) {
                $element = call_user_func($element);
            } elseif (is_object($element)
                && method_exists($element, 'html')
            ) {
                $element = $element->html();
            } elseif (is_array($element)) {
                $element = $this->buildElements($element);
            }
        }

        return $elements;
    }
}
