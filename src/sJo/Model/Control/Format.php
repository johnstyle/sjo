<?php

namespace sJo\Model\Control;

use sJo\Data\Type;

trait Format
{
    private function format ()
    {
        foreach ($this->getTableFields() as $name => $attr) {

            $this->{$name} = Type::set($attr['type'], $this->{$name}, $attr['length']);
        }
    }
}
