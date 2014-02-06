<?php

namespace sJo\Modules\User\Model;

use \sJo\Libraries as Lib;

abstract class UserMap extends Lib\DataObject
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
        'id'    => array(
            'primary'   => true,
            'type'      => 'int'
        ),
        'name'  => array(
            'type'      => 'string'
        ),
        'email'     => array(
            'type'      => 'email'
        ),
        'password'  => array(
            'type'      => 'password'
        )
    );
}
