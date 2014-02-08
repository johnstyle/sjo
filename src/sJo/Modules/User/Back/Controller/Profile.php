<?php

namespace sJo\Modules\User\Controller;

use sJo\Core\Controller\Controller;

class Profile extends Controller
{
    public function update ()
    {
        parent::update('\sJo\Modules\User\Model\User');
    }
}
