<?php

namespace sJo\Modules\User\Model;

use sJo\Db\PDO\Drivers\Mysql as Db;
use sJo\Core;

class Auth extends Core\Model
{
    public function exists($email, $password)
    {
        return Db::instance()->value("SELECT `id` FROM `users` WHERE `email` = :email AND `password` = :password", array(
            'email' => $email,
            'password' => md5($password)
        ));
    }
}
