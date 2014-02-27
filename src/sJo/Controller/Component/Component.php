<?php

namespace sJo\Controller\Component;

use sJo\Controller\Controller;
use sJo\Object\Singleton;

class Component
{
    use Singleton;

    /** @var Session $session */
    public $session;
    /** @var Logger $logger */
    public $logger;

    public function __construct(Controller $controller = null)
    {
        $this->session = new Session($controller);
        $this->logger = new Logger($controller);
    }
}
