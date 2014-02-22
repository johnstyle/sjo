<?php

namespace sJo\Module\User\Model;

use sJo\Object\MysqlObject;

/**
 * @property $id int
 * @property $name string
 * @property $email string
 * @property $password string
 */
abstract class UserMap extends MysqlObject
{
    /** @var int $id ID */
    protected $id;
    /** @var string $name Name */
    protected $name;
    /** @var string $email Email */
    protected $email;
    /** @var string $password Password */
    protected $password;
    /** @var array $__map DB map */
    protected $__map = array(
        'table' => 'users',
        'columns' => array(
            'id' => array(
                'primary' => true,
                'type' => 'int',
                'length' => 11
            ),
            'name' => array(
                'type' => 'varchar',
                'length' => 20
            ),
            'email' => array(
                'type' => 'varchar',
                'length' => 100
            ),
            'password' => array(
                'type' => 'char',
                'length' => 32
            )
        )
    );
}
