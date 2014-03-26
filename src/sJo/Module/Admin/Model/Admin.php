<?php

namespace sJo\Module\Admin\Model;

use sJo\Encryption\Encrypter;
use \sJo\Libraries as Lib;
use sJo\Model\Auth;

class Admin extends AdminMap
{
    use Auth;

    public static function logged()
    {
        return new self (self::session()->id->val());
    }

    public function exists($email, $password)
    {
        return self::db()->value($this->getPrimaryKey(), array(
            'email' => $email,
            'password' => Encrypter::md5($password)
        ));
    }
}
