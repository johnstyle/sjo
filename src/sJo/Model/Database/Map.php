<?php

namespace sJo\Model\Database;

use sJo\Data\Type;
use sJo\Libraries\Arr;
use sJo\Request\Request;

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
                'primary' => null,
                'type' => null,
                'length' => null,
                'required' => null,
                'values' => null,
                'default' => null,
                'unique' => null
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

    public static function getPrimaryRequest ($type = 'GET')
    {
        return Request::env($type)->{static::getInstance()->getPrimaryKey()}->val();
    }

    public function getPrimaryKey ()
    {
        if (!$this->primaryKey) {

            if(isset($this->__map['columns'])) {

                foreach($this->__map['columns'] as $key=>$map) {

                    if(true === $map['primary']) {

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

    public function setTableColumnsValue ($name, $value = null)
    {
        if (in_array($name, $this->getTableColumnsName())) {

            $attr = $this->__map['columns'][$name];

            if (is_null($value)
                || '' === $value) {

                $default = $attr['default'];

                if (preg_match("#^:([[:alnum:]]+)$#", $default, $match)) {

                    $default = $this->{$match[1]};
                }

                $value = $default;

            } else {

                $value = Type::set($attr['type'], $value, $attr['length']);
            }

            $this->{$name} = $value;
        }
    }
}
