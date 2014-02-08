<?php

namespace sJo\Libraries;

abstract class DataObject
{
    public function __get($name)
    {
        if(isset($this->{$name})) {
            return $this->{$name};
        }

        return false;
    }

    public function __set($name, $value)
    {
        if(isset($this->{$name})) {
            $this->{$name} = $value;
        }

        return $this;
    }

    public function __isset($name)
    {
        return $name != '__map' && property_exists($this, $name);
    }

    public function __unset($name)
    {
        if(isset($this->{$name})) {
            unset($this->{$name});
        }
    }

    public function getProperties()
    {
        $vars = (object) get_object_vars($this);
        if (isset($vars->__map)) {
            unset($vars->__map);
        }
        return $vars;
    }
}
