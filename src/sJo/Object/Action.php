<?php

namespace sJo\Object;

trait Action
{
    public function __construct()
    {
        if($this->getPrimaryValue() !== null) {

            $result = self::db()->result(array(
                $this->getPrimaryKey() => $this->getPrimaryValue()
            ));

            if($result) {
                foreach($result as $key => $value) {
                    $this->{$key} = $value;
                }
            }
        }
    }

    public function delete()
    {
        if($this->getPrimaryValue()) {
            return self::db()->delete(array(
                $this->getPrimaryKey() => $this->getPrimaryValue()
            ));
        }

        return false;
    }

    public function save()
    {
        $where = null;
        if($this->getPrimaryValue()) {
            $where = array(
                $this->getPrimaryKey() => $this->getPrimaryValue()
            );
        }

        return self::db()->update((array) $this->getProperties(), $where);
    }
}
