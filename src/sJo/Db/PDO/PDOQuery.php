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
     * Resultat de la première colonne
     *
     * @param string $key
     * @param array $where
     * @return string
     */
    final public function value($key, array $where = null)
    {
        $query = '
            SELECT `' . $key . '` FROM `' . $this->table . '`
            WHERE ' . self::where($where);

        return $this->fetchColumn($query, $where);
    }

    final public static function where(array $where = null)
    {
        $query = array();
        foreach (array_keys($where) as $item) {
            $query[] = "`" . $item . "` = :" . $item;
        }
        return implode(' AND ', $query);
    }

    /**
     * Liste de résultats de la requete
     *
     * @param array $where
     * @return object
     */
    final public function results(array $where = null)
    {
        $query = '
            SELECT * FROM `' . $this->table . '`
            WHERE ' . self::where($where);

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
        $query = '
            SELECT * FROM `' . $this->table . '`
            WHERE ' . self::where($where);

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
            $values = Lib\Arr::extend($values, $where);

            $query = '
                UPDATE `' . $this->table . '`
                SET ' . $set . '
                WHERE ' . self::where($where);
        } else {
            $query = '
                INSERT INTO `' . $this->table . '` (`' . implode('`, `', array_keys($values)) . '`)
                VALUES(:' . implode(', :', $values) . ')';
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
        $query = '
            DELETE FROM `' . $this->table . '`
            WHERE ' . self::where($where);

        $this->req($query);

        return $this;
    }
}
