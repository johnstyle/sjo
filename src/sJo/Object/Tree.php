<?php

namespace sJo\Object;

class Tree
{
    protected $key;
    protected $items;
    protected $item;
    protected $name;

    public function __construct(array &$items = null, $key = null)
    {
        $this->key = $key;
        $this->items =& $items;
        $this->item =& $items;
    }

    public function recursive (array &$item, $name)
    {
        $this->name = $name;
        $this->item =& $item[$name];

        return $this;
    }

    public function __get($name)
    {
        if (!isset($this->item[$name])) {
            $this->item[$name] = null;
        }

        /** @var Tree $tree */
        $tree = new static ($this->items, $this->key);
        return $tree->recursive($this->item, $name);
    }

    public function __isset($name)
    {
        return array_key_exists($name, $this->item)
         && (!is_array($this->item[$name]) || count($this->item[$name]));
    }

    public function __set($name, $value)
    {
        $this->item[$name] = $value;
    }

    public function __unset($name)
    {
        if ($this->__isset($name)) {
            unset($this->item[$name]);
        }
    }

    public function isArray ()
    {
        return is_array($this->item);
    }

    public function getArray ()
    {
        return is_array($this->item) && count($this->item) ? $this->item : null;
    }

    public function exists ()
    {
        if(isset($this->item)) {
            if (is_array($this->item)) {
                return count($this->item) ? true : false;
            }
            return true;
        }

        return false;
    }

    public function val ($default = false, $empty = false)
    {
        if ($this->item !== null
            && (!$empty || ($empty && !empty($this->item)))) {
            return $this->item;
        }

        return $default;
    }

    public function value ($default = false, $empty = false)
    {
        return $this->val($default, $empty);
    }

    public function eq ($value)
    {
        return $this->item === $value;
    }

    public function destroy ()
    {
        $this->item = null;
        unset($this->item);
    }
}
