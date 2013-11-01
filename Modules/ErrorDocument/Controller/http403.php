<?php

namespace PHPTools\Modules\ErrorDocument\Controller;

class http403 extends \PHPTools\Controller
{
    public function __viewPreload()
    {
        http_response_code(403);
    }
}
