<?php

namespace sJo\Modules\User\Model;

use sJo\Core\Object;

abstract class UserMap extends Object\MysqlObject
{
    /** @var int ID */
    protected $id;
    /** @var string Name */
    protected $name;
    /** @var string Email */
    protected $email;
    /** @var string Password */
    protected $password;
    /** @var array DB map */
    protected $__map = array(
        'table' => 'users',
        'columns' => array(
            'id'    => array(
                'primary'   => true,
                'type'      => 'int',
                'length'    => 11
            ),
            'name'  => array(
                'type'      => 'varchar',
                'length'    => 20
            ),
            'email'     => array(
                'type'      => 'varchar',
                'length'    => 100
            ),
            'password'  => array(
                'type'      => 'char',
                'length'    => 32
            )
        )
    );
}
