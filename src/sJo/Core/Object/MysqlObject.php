<?php

namespace sJo\Core\Object;

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
        $this->__ActionConstruct();

        $primary = $this->getPrimaryKey();
        $this->{$primary} = $id;
    }
}
