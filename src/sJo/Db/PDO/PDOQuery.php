<?php

namespace sJo\Db\PDO;

use sJo\Libraries as Lib;

trait PDOQuery
{
    private $table;

    /**
     * @param $table
     * @return PDOQuery
     */
    final public function table ($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Liste de résultats
     *
     * @param string $key
     * @param string $value
     * @return array
     */
    final public function getList($key, $value)
    {
        $list = array();

        if ($results = $this->results()) {

            foreach ($results as $result) {
                if (isset($result->{$key})
                    && isset($result->{$value})) {
                    $list[$result->{$key}] = $result->{$value};
                }
            }
        }

        return $list;
    }

    /**
     * Resultat de la première colonne
     *
     * @param string $key
     * @param array $where
     * @return string
     */
    final public function value($key, array $where = null)
    {
        $queryWhere = self::setWhere($where);
        $querySelect = self::setSelect($key);

        $query = $querySelect . ' FROM `' . $this->table . '`' .  $queryWhere;

        return $this->fetchColumn($query, $where);
    }

    final public static function setSelect($names)
    {
        if (!is_array($names)) {

            $names = array($names);
        }

        return 'SELECT `' . implode('`, `', $names) . '`';
    }

    final public static function setWhere(array &$where = null)
    {
        if ($where) {

            $query = array();

            foreach ($where as $name=>$item) {

                if (!is_array($item)) {
                    $item = array(
                        'value' => $item
                    );
                }

                $item = Lib\Arr::extend(array(
                    'value' => null,
                    'operator' => '='
                ), $item);

                $query[] = "`" . $name . "` " . $item['operator'] . " :" . $name;

                $where[$name] = $item['value'];
            }

            return ' WHERE ' . implode(' AND ', $query);
        }

        return false;
    }

    /**
     * Liste de résultats de la requete
     *
     * @param array $where
     * @return object
     */
    final public function results(array $where = null)
    {
        $queryWhere = self::setWhere($where);

        $query = 'SELECT * FROM `' . $this->table . '`' . $queryWhere;

        return $this->fetchAll($query, $where);
    }

    /**
     * Resultat de la requete
     *
     * @param array $where
     * @return object
     */
    final public function result(array $where = null)
    {
        $queryWhere = self::setWhere($where);

        $query = 'SELECT * FROM `' . $this->table . '`' . $queryWhere;

        return $this->fetch($query, $where);
    }

    /**
     * Insertion / Mise à jour des données
     *
     * @param array $values
     * @param null|array $where
     * @return $this
     */
    final public function update(array $values, array $where = null)
    {
        if ($where) {

            $set = self::setValues($values);
            $queryWhere = self::setWhere($where);
            $values = Lib\Arr::extend($values, $where);

            $query = '
                UPDATE `' . $this->table . '`
                SET ' . $set .
                $queryWhere;
        } else {

            $valuesKeys = array_keys($values);

            $query = '
                INSERT INTO `' . $this->table . '` (`' . implode('`, `', $valuesKeys) . '`)
                VALUES(:' . implode(', :', $valuesKeys) . ')';
        }

        $this->req($query, $values);

        return $this;
    }

    final public static function setValues(array $values = null)
    {
        $query = array();
        foreach (array_keys($values) as $item) {
            $query[] = "`" . $item . "` = :" . $item;
        }
        return implode(', ', $query);
    }

    /**
     * Suppression des données
     *
     * @param null|array $where
     * @return $this
     */
    final public function delete(array $where = null)
    {
        $queryWhere = self::setWhere($where);

        $query = 'DELETE FROM `' . $this->table . '`' . $queryWhere;

        $this->req($query, $where);

        return $this;
    }
}
