<?php

namespace PHPTools\Modules\User\Core;

use \PHPTools\Db\PDO\Drivers\Mysql as Db;

class User
{
    public static function exists($login, $password)
    {
        return Db::instance()->value("SELECT `id` FROM `users` WHERE `email` = :login AND `password` = :password", array(
            'login' => $login,
            'password' => md5($password)
        ));
    }
}
