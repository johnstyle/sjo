<?php

namespace sJo\Model\Component;

trait Event
{
    private $__instance;

    private function event($event, $options = false)
    {
        $event = '__' . $event;

        if (method_exists($this->__instance, $event)) {
            return $this->__instance->{$event}($options);
        }

        return false;
    }
}
