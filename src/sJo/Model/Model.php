<?php

namespace sJo\Model;

use sJo\Model\Database\DatabaseInterface;
use sJo\Object\Singleton;
use sJo\Object\Entity;
use sJo\Request\Request;
use sJo\Model\Database\Map;

abstract class Model implements DatabaseInterface
{
    use Singleton;
    use Entity;
    use Control\Validate;
    use Control\Format;
    use Control\Error;
    use Map {
        Map::__construct as private __MapConstruct;
    }

    public function __construct($id = null)
    {
        if($id === null) {

            $id = Request::env('REQUEST')->{$this->getPrimaryKey()}->val();
        }

        $this->setPrimaryValue($id);
        $this->__MapConstruct();
    }
}
