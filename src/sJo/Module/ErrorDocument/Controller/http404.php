<?php

namespace sJo\Module\ErrorDocument\Controller;

use sJo\Controller\Controller;
use sJo\Libraries as Libs;

class http404 extends Controller
{
    public $message;

    public function __initView()
    {
        http_response_code(404);

        if(!$this->message) {
            $this->message = Libs\I18n::__('From htaccess');
        }

        $this->component->logger->error('Error 404: {message}', array(
            'message' => $this->message
        ));
    }
}
