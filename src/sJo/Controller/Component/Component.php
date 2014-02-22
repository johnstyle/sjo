<?php

namespace sJo\Controller\Component;

use sJo\Controller\Controller;
use sJo\Object\Singleton;

class Component
{
    use Singleton;

    /** @var Session $session */
    public $session;
    /** @var Request $request */
    public $request;
    /** @var Alert $alert */
    public $alert;
    /** @var Logger $logger */
    public $logger;

    public function __construct(Controller $controller = null)
    {
        $this->session = new Session($controller);
        $this->request = new Request($controller);
        $this->alert = new Alert($controller);
        $this->logger = new Logger($controller);
    }
}
