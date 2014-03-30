<?php

namespace sJo\View\Helper\Dom;

use sJo\Data\Validate;
use sJo\Exception\Exception;
use sJo\Libraries\Arr;
use sJo\Libraries\I18n;
use sJo\Object\Closure;
use sJo\View\Helper\Grid;

abstract class Dom
{
    const DEFAULT_WRAPPER = 'elements';

    protected $wrappers;
    protected $elements;

    protected static $attributes = array();

    protected static $defaultAttributes = array(
        'grid' => null,
        'tagname' => null,
        'attributes' => array(
            'id' => null,
            'class' => null,
            'title' => null,
            'alt' => null,
            'placeholder' => null,
            'data' => null,
            'autofocus' => null,
            'type' => null,
            'name' => null,
            'value' => null,
            'action' => null,
            'method' => null,
            'href' => null,
            'src' => null,
            'target' => null,
        ),
        'elements' => null,
        'label' => null,
        'legend' => null,
        'tooltip' => null,
        'options' => null,
        'icon' => null,
        'items' => null,
        'container' => null
    );

    private static $frameworkName = 'Bootstrap';

    public function __construct($elements = null)
    {
        if (!$this->wrappers) {
            $this->wrappers = array(static::DEFAULT_WRAPPER);
        }

        $this->elements = $elements;

        $this->setElements();
    }

    protected function setElements()
    {
        if (!$this->elements) {
            $this->elements = array(array(static::DEFAULT_WRAPPER => null));
        }

        foreach ($this->elements as &$element) {

            if (!is_array($element)) {
                $element = array(static::DEFAULT_WRAPPER => $element);
            }

            if (is_array($element)
                && empty($element)) {
                $element = null;
            }

            foreach ($this->wrappers as $wrapper) {

                if (!isset($element[$wrapper])) {

                    if ($wrapper == static::DEFAULT_WRAPPER) {
                        $element = array($wrapper => $element);
                    } else {
                        $element[$wrapper] = null;
                    }
                }

                if (!is_null($element[$wrapper])
                    && !is_array($element[$wrapper])) {
                    $element[$wrapper] = array($element[$wrapper]);
                }
            }

            $element = Arr::extend(static::$attributes, $element);
            $element = Arr::extend(static::$defaultAttributes, $element);
            $element = $this->setElement($element);
        }

    }

    protected function setElement($element)
    {
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
        return new $class (func_num_args() ? func_get_args() : null);
    }

    public function render(array $options = null)
    {
        echo $this->html($options);
    }

    public function html(array $options = null)
    {
        $options = Arr::extend(array(
            'method' => $this->getClass($this),
            'callback' => null
        ), $options);

        $grids = false;
        $html = array();

        if ($this->elements) {

            foreach($this->elements as $element) {
                if(isset($element['grid'])) {
                    $grids = true;
                }
            }

            foreach ($this->elements as $element) {

                if($options['callback']
                    && is_callable($options['callback'])) {
                    $element = call_user_func($options['callback'], $element);
                }

                $htmlElement = $this->inc(strtolower($options['method']), $this->buildElements($element));

                if ($grids) {

                    $html[] = array(
                        'grid' => $element['grid'] ? $element['grid'] : 12,
                        'elements' => array($htmlElement)
                    );

                } else {

                    $html[] = $htmlElement;
                }
            }
        }

        if (count($html)) {

            if ($grids) {

                return Grid::create($html)->html();
            }

            return implode('', $html);
        }

        return false;
    }

    protected function inc()
    {
        $args = func_get_args();
        $file = array_shift($args);
        $args = $args[0];

        $closure = new Closure();

        foreach ($args as $name => $value) {
            if (!in_array($name, array('attributes'))) {
                $closure->{$name} = $value;
            }
        }

        $closure->attributes = function () use ($args) {
            if (!isset($args['attributes'])) {
                return false;
            }
            $output = '';
            foreach ($args['attributes'] as $name => $value) {
                if(!Validate::isEmpty($value)) {
                    switch ($name) {
                        case 'data':
                            $name = 'data-' . $name;
                            break;
                    }
                    $output .= ' ' . $name . '="' . $value . '"';
                }
            }
            return $output;
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
        foreach ($elements as $name=>&$element) {

            if (in_array($name, $this->wrappers)
                && is_array($element)) {

                foreach ($element as &$el) {

                    if (is_object($el)
                        && method_exists($el, 'html')
                        && get_parent_class($el) == __CLASS__) {

                        $el = $el->html();

                    } elseif (Validate::isCallable($el)
                        && get_parent_class($el) == __CLASS__) {

                        $el = call_user_func($el);

                    } elseif (is_array($el)) {

                        $el = $this->buildElements($el);
                    }
                }
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
