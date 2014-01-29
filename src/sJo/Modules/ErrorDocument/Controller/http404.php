<?php

namespace sJo\Modules\ErrorDocument\Controller;

use sJo\Core\Controller;
use sJo\Libraries\I18n;

class http404 extends Controller
{
    public $message;

    public function __viewLoaded()
    {
        http_response_code(404);

        if(!$this->message) {
            $this->message = I18n::__('From htaccess');
        }

        $this->Logger->error('Error 404: {message}', array(
                'message' => $this->message
            ));
    }
}
