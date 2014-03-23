<?php

namespace sJo\Module\ErrorDocument\Controller;

use sJo\Controller\Controller;
use sJo\Libraries as Libs;
use sJo\Loader\Logger;

class http404 extends Controller
{
    public $message;

    public function __initView()
    {
        http_response_code(404);

        if(!$this->message) {
            $this->message = Libs\I18n::__('From htaccess');
        }

        Logger::getInstance()->error('Error 404: {message}', array(
            'message' => $this->message
        ));
    }
}
