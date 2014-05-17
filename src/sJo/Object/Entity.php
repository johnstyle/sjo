<?php

namespace sJo\Object;

use sJo\Exception\Exception;
use sJo\Libraries\I18n;
use sJo\Libraries as Lib;
use sJo\Request\Request;

trait Entity
{
    public function __get($name)
    {
        if(!$this->__isset($name)) {

            Exception::error(I18n::__('Property %s->%s does not exist.', get_called_class(), $name));
        }

        return $this->{$name};
    }

    public function __set($name, $value)
    {
        if(!$this->__isset($name)) {

            Exception::error(I18n::__('Property %s->%s does not exist.', get_called_class(), $name));
        }

        $this->{$name} = $value;

        return $this;
    }

    public function __isset($name)
    {
        $class = new \ReflectionClass($this);

        return $class->hasProperty($name)
            && !preg_match("#^__#", $name);
    }

    public function __unset($name)
    {
        if(!$this->__isset($name)) {

            Exception::error(I18n::__('Property %s->%s does not exist.', get_called_class(), $name));
        }

        unset($this->{$name});
    }

    public function getProperties(array $excludes = array())
    {
        $properties = new \stdClass();
        $class = new \ReflectionClass($this);

        foreach($class->getProperties() as $property) {

            if(!preg_match("#^__#", $property->name)
                && !in_array($property->name, $excludes)) {

                $properties->{$property->name} = $this->{$property->name};
            }
        }

        return $properties ? $properties : null;
    }

    public function request($name)
    {
        if (Request::env('POST')->{$name}->exists()) {

            return Request::env('POST')->{$name}->val();
        }

        return isset($this->{$name}) ? $this->{$name} : null;
    }
}
