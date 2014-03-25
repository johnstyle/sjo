<?php

namespace sJo\View\Helper\Dom;

use sJo\Exception\Exception;
use sJo\Libraries\Arr;
use sJo\Libraries\I18n;
use sJo\Object\Closure;

abstract class Dom
{
    const DEFAULT_ELEMENT = '__default__';

    protected $elements;
    protected static $defaultAttributes = array(
        'tagname' => null,
        'id' => null,
        'class' => null,
        'title' => null,
        'alt' => null,
        'label' => null,
        'legend' => null,
        'tooltip' => null,
        'placeholder' => null,
        'data' => null,
        'type' => null,
        'name' => null,
        'value' => null,
        'autofocus' => null,
        'action' => null,
        'method' => null,
        'href' => null,
        'src' => null,
        'target' => null,
        'icon' => null,
        'items' => null,
        'pull' => null,
        'container' => null,
        'elements' => null
    );

    private static $frameworkName = 'Bootstrap';

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

    protected function setElement($element, $wrapper = null, $array = null)
    {
        if ($array && !is_array($element)) {
            $element = array($array => $element);
        }

        $element = Arr::extend(self::$defaultAttributes, $element);

        if ($wrapper) {

            if (!isset($element[$wrapper])) {

                if (isset($element['__default__'])) {
                    $element[$wrapper] = $element['__default__'];
                } else {
                    $element = array($wrapper => $element);
                }
            }

            if (!is_array($element[$wrapper])) {
                $element[$wrapper] = array($element[$wrapper]);
            }
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

    public function display(array $options = null)
    {
        echo $this->html($options);
    }

    public function html(array $options = null)
    {
        $options = Arr::extend(array(
            'method' => $this->getClass($this),
            'callback' => null
        ), $options);

        $html = '';

        if ($this->elements) {
            foreach ($this->elements as $element) {
                if($options['callback'] && is_callable($options['callback'])) {
                    $element = call_user_func($options['callback'], $element);
                }
                $html .= $this->inc(strtolower($options['method']), $this->buildElements($element));
            }
        }

        return $html;
    }

    protected function inc()
    {
        $args = func_get_args();
        $file = array_shift($args);

        $closure = new Closure();

        if (count($args)) {
            foreach ($args as $arg) {
                if (is_array($arg)) {
                    foreach ($arg as $name => $value) {
                        $closure->{$name} = $value;
                    }
                } else {
                    if (!isset($closure->elements)) {
                        $closure->elements = array();
                    }
                    $closure->elements[] = $arg;
                }
            }
        }

        $closure->attributes = function () use ($args) {
            $output = '';
            foreach ($args as $arg) {
                foreach ($arg as $name => $value) {
                    if($value) {
                        $output .= ' ' . $name . '="' . $value . '"';
                    }
                }
            }
            echo $output;
        };

        $filename = realpath(dirname(__FILE__)) . '/' . self::$frameworkName . '/' . $file . '.php';
        if (file_exists($filename)) {
            ob_start();
            $closure->inc($filename);
            return ob_get_clean();
        } else {
            Exception::error(I18n::__('helper %s/%s do not exists.', self::$frameworkName, ucfirst(basename($filename, '.php'))));
        }
        return false;
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

    final public function getClass($element)
    {
        if (is_object($element)) {
            $class = new \ReflectionClass($element);
            return $class->getShortName();
        }

        return null;
    }
}
