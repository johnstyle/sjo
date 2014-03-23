<?php

namespace sJo\Controller;

use sJo\Data\Type;
use sJo\Model\MysqlObject;

trait Format
{
    private function format (MysqlObject $instance)
    {
        foreach ($instance->getTableFields() as $name => $attr) {

            $instance->{$name} = Type::set($attr['type'], $instance->{$name}, $attr['length']);
        }
    }
}
