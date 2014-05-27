<?php

namespace sJo\Module\ErrorDocument\Controller;

use sJo\Controller\Controller;
use sJo\Libraries as Libs;
use sJo\Loader\Logger;

class Http403 extends Controller
{
    public $message;

    public function __initView()
    {
        http_response_code(403);

        if(!$this->message) {
            $this->message = Libs\I18n::__('From htaccess');
        }

        Logger::getInstance()->error('Error 403: {message}', array(
            'message' => $this->message
        ));
    }
}
