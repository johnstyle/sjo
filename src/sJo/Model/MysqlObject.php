<?php

namespace sJo\Model;

use sJo\Db\PDO\Drivers\Mysql as Db;
use \sJo\Libraries as Lib;
use sJo\Object\Singleton;
use sJo\Request\Request;

abstract class MysqlObject
{
    use Singleton;
    use Entity;
    use Map {
        Map::__construct as private __MapConstruct;
    }
    use Action {
        Action::__construct as private __ActionConstruct;
    }

    public function __construct($id = null)
    {
        if($id === null) {
            $id = Request::env('REQUEST')->{$this->getPrimaryKey()}->val();
        }

        $this->setPrimaryValue($id);
        $this->__MapConstruct();
        $this->__ActionConstruct();
    }

    public function db ()
    {
        return Db::getInstance()->table($this->getTableName());
    }
}
