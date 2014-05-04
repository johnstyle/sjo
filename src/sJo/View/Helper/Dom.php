<?php

namespace sJo\View\Helper;

use sJo\Data\Handle;
use sJo\Data\Validate;
use sJo\Exception\Exception;
use sJo\Libraries\Arr;
use sJo\Libraries\I18n;
use sJo\Object\Closure;
use sJo\View\Helper\Drivers\Html\Grid;

abstract class Dom
{
    const DEFAULT_ELEMENT_ATTRIBUTE_CLASS   = null;
    const DEFAULT_WRAPPER = null;
    const DEFAULT_DRIVER  = 'Html';
    const TYPE            = 'container';
    const TPL_NAME        = null;

    protected static $element = array();
    protected static $defaultElement = array(
        'grid'       => null,
        'tagname'    => null,
        'attributes' => array(
            'id'          => null,
            'class'       => null,
            'title'       => null,
            'data'        => null
        ),
        'elements'   => null,
        'legend'     => null,
        'tooltip'    => null,
        'options'    => null,
        'icon'       => null,
        'items'      => null,
        'container'  => null
    );
    protected $wrappers = array();
    protected $wrapper;
    private $elements;

    public function __construct($elements = null)
    {
        if (!static::DEFAULT_WRAPPER) {

            switch (static::TYPE) {

                case 'container':
                    $this->wrapper = 'elements';
                    break;
            }

        } else {

            $this->wrapper = static::DEFAULT_WRAPPER;
        }

        if (!$this->wrappers
            && $this->wrapper
        ) {
            $this->wrappers = array($this->wrapper);
        }

        $this->elements = $elements;

        static::$element = Arr::extend(static::$defaultElement, static::$element, true);

        $this->setElements();
    }

    private function setElements()
    {
        if (!$this->elements
            && $this->wrapper
        ) {
            $this->elements = array(array($this->wrapper => null));
        }

        foreach ($this->elements as &$element) {

            if (!is_array($element)) {
                $element = array($this->wrapper => $element);
            }

            if (is_array($element)
                && empty($element)
            ) {
                $element = null;
            }

            // Check attributes
            foreach ($element as $name=>$value) {
                if (array_key_exists($name, static::$element['attributes'])) {
                    if (!isset($element['attributes'])) {
                        $element['attributes'] = array();
                    }
                    $element['attributes'][$name] = $value;
                    unset($element[$name]);
                }
            }
            if (isset($element['attributes'])
                && !count($element['attributes'])) {

                unset($element['attributes']);
            }

            // Check wrappers
            foreach ($this->wrappers as $wrapper) {

                if (!isset($element[$wrapper])) {

                    if ($wrapper == $this->wrapper
                        && !isset($element['attributes'])) {

                        $element = array($wrapper => $element);

                    } else {

                        $element[$wrapper] = null;
                    }
                }

                if (!is_null($element[$wrapper])
                    && !is_array($element[$wrapper])
                ) {
                    $element[$wrapper] = array($element[$wrapper]);
                }
            }

            // Merge defaut element
            $element = Arr::extend(static::$element, $element, true);
            $element = $this->setElement($element);

            // Force elements
            if (static::DEFAULT_ELEMENT_ATTRIBUTE_CLASS) {
                $element['attributes']['class'] .= ' ' . static::DEFAULT_ELEMENT_ATTRIBUTE_CLASS;
            }
        }
    }

    protected function setElement($element)
    {
        return $element;
    }

    protected function addElements($element)
    {
        return $this->elements[0][$this->wrapper] = $element;
    }

    public function render(array $options = null)
    {
        echo $this->html($options);
    }

    public function html(array $options = null)
    {
        $options = Arr::extend(
            array(
                'callback' => null
            ),
            $options
        );

        if ($this->elements) {

            $grids = false;
            $html  = array();

            foreach ($this->elements as $element) {

                if (isset($element['grid'])) {

                    $grids = true;
                }
            }

            foreach ($this->elements as $element) {

                if ($options['callback']
                    && is_callable($options['callback'])
                ) {
                    $element = call_user_func($options['callback'], $element);
                }

                $htmlElement = $this->inc($this->buildElements($element));

                if ($grids) {

                    $html[] = array(
                        'grid'     => $element['grid'] ? $element['grid'] : 12,
                        'elements' => array($htmlElement)
                    );
                } else {

                    $html[] = $htmlElement;
                }
            }

            if (count($html)) {

                if ($grids) {

                    return Grid::create($html)->html();
                }

                return implode('', $html);
            }
        }

        return false;
    }

    final protected function inc(array $args)
    {
        $closure = new Closure();

        foreach ($args as $name => $value) {
            if (!in_array($name, array('attributes'))) {
                $closure->{$name} = $value;
            }
        }

        $closure->attributes = function (array $attributes = array()) use ($args) {

            if (!isset($args['attributes'])) {
                return null;
            }

            $output = '';

            foreach ($attributes as $name => $value) {
                if (isset($args['attributes'][$name])) {
                    $args['attributes'][$name] .= ' ' . $value;
                } else {
                    $args['attributes'][$name] = $value;
                }
            }

            foreach ($args['attributes'] as $name => $value) {

                if (!Validate::isEmpty($value)) {

                    switch ($name) {

                        case 'data':
                            foreach ($value as $subname=>$subvalue) {
                                $output .= ' ' . $name . '-' . $subname . '="' . trim($subvalue) . '"';
                            }
                            break;

                        default:
                            $output .= ' ' . $name . '="' . trim($value) . '"';
                            break;
                    }
                }
            }

            return $output;
        };

        $closure->attribute = function ($name) use ($args) {

            if (!isset($args['attributes'])) {
                return null;
            }

            return isset($args['attributes'][$name]) ? $args['attributes'][$name] : null;
        };

        $filename = $this->getTplFile();

        if (file_exists($filename)) {

            ob_start();
            $closure->inc($filename);
            return ob_get_clean();

        } else {

            Exception::error(
                I18n::__('helper %s do not exists.', $filename . '.php')
            );
        }

        return false;
    }

    final public function getTplFile()
    {
        $file = null;

        $class = new \ReflectionClass(get_called_class());
        $nameCurrent  = strtolower($class->getShortName());
        $nameDefault  = (static::TPL_NAME ? static::TPL_NAME : static::TYPE);

        $pathView = realpath(dirname(__FILE__)) . '/View/';
        $pathCurrent = $pathView . Handle::cutLast($class->getNamespaceName(), '\\') . '/';
        $pathDefault = $pathView . self::DEFAULT_DRIVER . '/';

        foreach (array($pathCurrent, $pathDefault) as $path) {

            $file = $path . $nameCurrent . '.php';

            if (file_exists($file)) {
                return $file;
            }

            $file = $path . $nameDefault . '.php';

            if (file_exists($file)) {
                return $file;
            }
        }

        return $file;
    }

    final protected function buildElements($elements)
    {
        foreach ($elements as $name => &$element) {

            if (in_array($name, $this->wrappers)
                && is_array($element)
            ) {

                foreach ($element as &$el) {

                    if (is_object($el)
                        && method_exists($el, 'html')
                        && in_array(__CLASS__, class_parents($el))
                    ) {

                        $el = $el->html();
                    } elseif (Validate::isCallable($el)
                        && in_array(__CLASS__, class_parents($el))
                    ) {

                        $el = call_user_func($el);
                    } elseif (is_array($el)) {

                        $el = $this->buildElements($el);
                    }
                }
            }
        }

        return $elements;
    }

    /**
     * @return Dom
     */
    public static function create()
    {
        $class = get_called_class();

        return new $class (func_num_args() ? func_get_args() : null);
    }

    protected static function createStatic($className, $elements)
    {
        $reflectionClass = new \ReflectionClass(get_called_class());
        $className =  '\\' . $reflectionClass->getNamespaceName() . '\\' . $className;
        return $className::create($elements);
    }
}
