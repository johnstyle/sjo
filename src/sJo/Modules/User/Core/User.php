<?php

namespace sJo\Modules\User\Core;

use \sJo\Db\PDO\Drivers\Mysql as Db;
use \sJo\Libraries as Lib;

class User extends Lib\DataObject
{
    protected $id;
    protected $name;
    protected $email;

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
}
