<?php

namespace sJo\Controller\Component;

use sJo\Controller\Controller;
use sJo\Model\Singleton;

class Component
{
    use Singleton;

    /** @var Session $session */
    public $session;
    /** @var Alert $alert */
    public $alert;
    /** @var Logger $logger */
    public $logger;

    public function __construct(Controller $controller = null)
    {
        $this->session = new Session($controller);
        $this->alert = new Alert($controller);
        $this->logger = new Logger($controller);
    }
}
