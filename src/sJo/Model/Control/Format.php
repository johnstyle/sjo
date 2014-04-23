<?php

namespace sJo\Model\Control;

use sJo\Data\Type;

trait Format
{
    private function format ()
    {
        foreach ($this->getTableColumns() as $name => $attr) {

            $this->{$name} = Type::set($attr['type'], $this->{$name}, $attr['length']);
        }
    }
}
