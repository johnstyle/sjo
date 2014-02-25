<?php

namespace sJo\Model;

use sJo\Exception\Exception;
use sJo\Libraries\I18n;

trait Entity
{
    public function __get($name)
    {
        if($this->__isset($name)) {
            return $this->{$name};
        } else {
            Exception::error(I18n::__('Property %s->%s does not exist.', get_called_class(), $name));
        }

        return false;
    }

    public function __set($name, $value)
    {
        if($this->__isset($name)) {
            $this->{$name} = $value;
        } else {
            Exception::error(I18n::__('Property %s->%s does not exist.', get_called_class(), $name));
        }

        return $this;
    }

    public function __isset($name)
    {
        $class = new \ReflectionClass($this);
        return $class->hasProperty($name) && $name != '__map';
    }

    public function __unset($name)
    {
        if($this->__isset($name)) {
            unset($this->{$name});
        } else {
            Exception::error(I18n::__('Property %s->%s does not exist.', get_called_class(), $name));
        }
    }

    public function getProperties()
    {
        $properties = new \stdClass();
        $class = new \ReflectionClass($this);
        foreach($class->getProperties() as $property) {
            if(!in_array($property->name, array('__map', '__instance'))) {
                $properties->{$property->name} = $this->{$property->name};
            }
        }
        return $properties ? $properties : null;
    }
}
