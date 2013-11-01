<?php

namespace PHPTools\Modules\ErrorDocument\Controller;

class http404 extends \PHPTools\Controller
{
    public function __viewPreload()
    {
        http_response_code(404);
    }
}
