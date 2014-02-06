<?php

namespace sJo\Modules\User\Model;

use \sJo\Db\PDO\Drivers\Mysql as Db;
use \sJo\Libraries as Lib;

class User extends UserMap
{
    public function __construct($id = false)
    {
        if($id === false) {
            $id = Lib\Env::session('token');
        }

        $user = Db::instance()->result("SELECT id, name, email FROM users WHERE id = :id ", array(
            'id' => $id
        ));

        if($user) {
            foreach($user as $name => $value) {
                $this->{$name} = $value;
            }
        }
    }

    public static function current()
    {
        return new self();
    }

    public static function exists($email, $password)
    {
        return Db::instance()->value("SELECT `id` FROM `users` WHERE `email` = :email AND `password` = :password", array(
            'email' => $email,
            'password' => md5($password)
        ));
    }
}
