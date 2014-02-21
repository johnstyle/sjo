<?php

namespace sJo\Object;

trait Map
{
    private $primaryKey;
    private $tableName;

    public function setPrimaryValue ($value)
    {
        $this->{$this->getPrimaryKey()} = $value;
    }

    public function getPrimaryValue ()
    {
        return $this->{$this->getPrimaryKey()};
    }

    public function getPrimaryKey ()
    {
        if (!$this->primaryKey) {
            if(isset($this->__map['columns'])) {
                foreach($this->__map['columns'] as $key=>$map) {
                    if(isset($map['primary']) && $map['primary'] === true) {
                        $this->primaryKey = $key;
                        break;
                    }
                }
            }
        }

        return $this->primaryKey;
    }

    public function getTableName ()
    {
        if (!$this->tableName) {
            if(isset($this->__map['table'])) {
                $this->tableName = $this->__map['table'];
            }
        }

        return $this->tableName;
    }
}
