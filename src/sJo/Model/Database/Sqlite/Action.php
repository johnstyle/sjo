<?php

namespace sJo\Model\Database\Sqlite;

trait Action
{
    public function __construct()
    {
        if(null !== $this->getPrimaryValue()) {

            $result = static::db()->result(array(
                $this->getPrimaryKey() => $this->getPrimaryValue()
            ));

            if($result) {

                foreach($result as $key => $value) {

                    $this->{$key} = $value;
                }
            }
        }
    }

    protected function delete()
    {
        if($this->getPrimaryValue()) {

            return static::db()->delete(array(
                $this->getPrimaryKey() => $this->getPrimaryValue()
            ));
        }

        return false;
    }

    protected function save()
    {
        $where = null;

        if($this->getPrimaryValue()) {

            $where = array(
                $this->getPrimaryKey() => $this->getPrimaryValue()
            );
        }

        $db = static::db()->update((array) $this->getProperties(), $where);

        if ($db->lastinsertid()) {

            $this->setPrimaryValue($db->lastinsertid());
        }

        return $db;
    }
}
