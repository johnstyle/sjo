<?php

namespace sJo\Module\User\Model;

use \sJo\Libraries as Lib;
use sJo\Request\Request;

class User extends UserMap
{
    public static function logged()
    {
        return new self (Request::env('SESSION')->id->val());
    }

    public function exists($email, $password)
    {
        return self::db()->value($this->getPrimaryKey(), array(
            'email' => $email,
            'password' => md5($password)
        ));
    }
}
