<?php

namespace PHPTools\Modules\User\Model;

use \PHPTools\Db\PDO\Drivers\Mysql as Db;

class Auth extends \PHPTools\Model
{
    public function exists($email, $password)
    {
        return Db::instance()->value("SELECT `id` FROM `users` WHERE `email` = :email AND `password` = :password", array(
            'email' => $email,
            'password' => md5($password)
        ));
    }
}
