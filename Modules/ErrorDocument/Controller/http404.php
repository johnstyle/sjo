<?php

namespace PHPTools\Modules\ErrorDocument\Controller;

use PHPTools\Libraries\I18n;

class http404 extends \PHPTools\Controller
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
