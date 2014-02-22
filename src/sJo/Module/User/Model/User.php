<?php

namespace sJo\Module\User\Model;

use \sJo\Libraries as Lib;

class User extends UserMap
{
    public function __construct($id = null)
    {
        if($id === null) {
            $id = Lib\Env::session('token');
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
