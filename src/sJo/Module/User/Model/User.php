<?php

namespace sJo\Module\User\Model;

use \sJo\Libraries as Lib;
use sJo\Request\Request;

class User extends UserMap
{
    public function __construct($id = null)
    {
        if($id === null) {
            $id = Request::env('SESSION')->id->val();
        }

        parent::__construct($id);
    }

    public function exists($email, $password)
    {
        return self::db()->value($this->getPrimaryKey(), array(
            'email' => $email,
            'password' => md5($password)
        ));
    }
}
