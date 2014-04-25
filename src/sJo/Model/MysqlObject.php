<?php

namespace sJo\Model;

use sJo\Db\PDO\Drivers\Mysql as Db;
use sJo\Model\Database\Mysql\Action;

abstract class MysqlObject extends Model
{
    use Action {
        Action::__construct as private __ActionConstruct;
    }

    public function __construct($id = null)
    {
        parent::__construct($id);

        $this->__ActionConstruct();
    }

    final public function db ()
    {
        return Db::getInstance()->table($this->getTableName());
    }
}
