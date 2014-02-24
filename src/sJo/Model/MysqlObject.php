<?php

namespace sJo\Model;

use sJo\Db\PDO\Drivers\Mysql as Db;

abstract class MysqlObject
{
    use Singleton;
    use Map;
    use Entity;
    use Action {
        Action::__construct as private __ActionConstruct;
    }

    public function __construct($id = null)
    {
        $this->setPrimaryValue($id);
        $this->__ActionConstruct();
    }

    public function db ()
    {
        return Db::getInstance()->table($this->getTableName());
    }
}
