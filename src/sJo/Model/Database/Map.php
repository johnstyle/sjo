<?php

namespace sJo\Model\Database;

use sJo\Libraries\Arr;

trait Map
{
    /** @var string $primaryKey Primary key name */
    private $primaryKey;
    /** @var string $tableName Table name */
    private $tableName;
    /** @var array $__map DB map */
    protected $__map;

    public function __construct ()
    {
        foreach ($this->__map['columns'] as &$column) {
            $column = Arr::extend(array(
                'type' => null,
                'length' => null,
                'required' => null,
                'values' => null,
            ), $column);
        }
    }

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

    public function getTableColumns ()
    {
        return $this->__map['columns'];
    }

    public function getTableColumnsName ()
    {
        return array_keys($this->__map['columns']);
    }
}
