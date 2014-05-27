<?php

namespace sJo\Module\Admin\Model\Map;

use sJo\Model\MysqlObject;

/**
 * @property $id int
 * @property $name string
 * @property $email string
 * @property $password string
 */
abstract class AdminMap extends MysqlObject
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
        'table' => 'admin',
        'columns' => array(
            'id' => array(
                'primary' => true,
                'type' => 'int',
                'length' => 11
            ),
            'name' => array(
                'type' => 'varchar',
                'length' => 20,
                'required' => true
            ),
            'email' => array(
                'type' => 'email',
                'length' => 100,
                'required' => true
            ),
            'password' => array(
                'type' => 'md5',
                'length' => 32
            )
        )
    );
}
