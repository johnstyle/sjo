<?php

namespace sJo\View\Helper\Dom;

use sJo\Core;
use sJo\Libraries\File;
use sJo\Libraries\I18n;

abstract class Dom
{
    const DEFAULT_ELEMENT = '__default__';

    private static $view;
    private static $frameworkName = 'Bootstrap';
    protected $elements;

    public function __construct($elements = null)
    {
        $this->elements = $elements;

        if (!$this->elements) {
            $this->elements = array(array(self::DEFAULT_ELEMENT => null));
        }

        foreach ($this->elements as &$element) {
            if (!is_array($element)) {
                $element = array(self::DEFAULT_ELEMENT => $element);
            }
        }

        $this->setElements();
    }

    protected function setElements()
    {
        foreach ($this->elements as &$element) {
            $element = $this->setElement($element);
        }
    }

    protected function setElement($element)
    {
        if (!isset($element['elements'])) {
            $element = array('elements' => $element);
        }

        if (!is_array($element['elements'])) {
            $element['elements'] = array($element['elements']);
        }

        return $element;
    }

    public static function setFrameworkName($name)
    {
        self::$frameworkName = $name;
    }

    /**
     * @return Dom
     */
    public static function create()
    {
        $class = get_called_class();
        return new $class(func_num_args() ? func_get_args() : null);
    }

    public function display($options = null)
    {
        echo $this->html($options);
    }

    public function html()
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

        $filename = realpath(dirname(__FILE__)) . '/' . self::$frameworkName . '/' . $file . '.php';
        if (file_exists($filename)) {
            ob_start();
            require $filename;
            return ob_get_clean();
        } else {
            Core\Exception::error(I18n::__('helper %s/%s do not exists.', self::$frameworkName, ucfirst(basename($filename, '.php'))));
        }
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
