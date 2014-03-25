<?php

namespace sJo\Module\Admin\Model;

use sJo\Encryption\Encrypter;
use \sJo\Libraries as Lib;
use sJo\Request\Request;

class Admin extends AdminMap
{
    public static function logged()
    {
        return new self (Request::env('SESSION')->id->val());
    }

    public function exists($email, $password)
    {
        return self::db()->value($this->getPrimaryKey(), array(
            'email' => $email,
            'password' => Encrypter::md5($password)
        ));
    }
}
