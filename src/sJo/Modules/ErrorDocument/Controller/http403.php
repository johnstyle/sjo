<?php

namespace sJo\Modules\ErrorDocument\Controller;

use sJo\Core;
use sJo\Libraries as Libs;

class http403 extends Core\Controller
{
    public $message;

    public function __viewLoaded()
    {
        http_response_code(403);

        if(!$this->message) {
            $this->message = Libs\I18n::__('From htaccess');
        }

        $this->Logger->error('Error 403: {message}', array(
                'message' => $this->message
            ));
    }
}
