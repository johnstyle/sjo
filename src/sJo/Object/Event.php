<?php

namespace sJo\Object;

trait Event
{
    private $instance;

    private function event($event, $options = false)
    {
        $event = '__' . $event;

        if (method_exists($this->instance, $event)) {
            return $this->instance->{$event}($options);
        }

        return false;
    }
}
