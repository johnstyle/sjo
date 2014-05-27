<?php

namespace sJo\Module\Admin\Model;

use sJo\Encryption\Encrypter;
use sJo\Model\Component\Auth;

class Admin extends Map\AdminMap
{
    use Auth;

    public static function logged()
    {
        return new static (static::getId());
    }

    public function exists($email, $password)
    {
        return static::db()->value($this->getPrimaryKey(), array(
            'email' => $email,
            'password' => Encrypter::md5($password)
        ));
    }
}
