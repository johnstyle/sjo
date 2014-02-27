<?php

namespace sJo\Object;

trait Tree
{
    private $var;
    private $name;
    private $data;

    public function recursive (array &$data, $name)
    {
        $this->name = $name;
        $this->data =& $data[$name];

        return $this;
    }

    public function __toString()
    {
        return (string) (is_array($this->data) ? true : (string) $this->data);
    }

    public function __get($name)
    {
        if (isset($this->data[$name])) {
            $obj = new self();
            return $obj->recursive($this->data, $name);
        }

        return null;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __unset($name)
    {
        if (isset($this->data[$name])) {
            unset($this->data[$name]);
        }
    }

    public function isArray ()
    {
        return is_array($this->data);
    }

    public function getArray ()
    {
        return is_array($this->data) && count($this->data) ? $this->data : null;
    }
}
