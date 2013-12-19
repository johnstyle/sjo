<?php

/**
 * Base de données Drivers
 *
 * PHP version 5
 *
 * @package  sJo
 * @category Db
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\Db\PDO;

use \sJo\Libraries as Lib;

/**
 * Base de données Drivers
 *
 * @package  sJo
 * @category Db
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
abstract class PDOCore extends \PDO
{
    /**
     * Instances
     *
     * @var array
     * @access private
     */
    private static $instances = array();

    /**
     * Liste des connexions
     *
     * @var array
     * @access private
     */
    private static $auth = array();

    /**
     * Total Queries
     *
     * @var int
     * @access private
     */
    private static $count = 0;

    /**
     * Insertion / Mise à jour des données simplifiée
     *
     * @param string $table
     * @param array $data
     * @param array $where
     * @return $this
     */
    public function update($table, array $data, array $where = array())
    {
        $keysQuery = array_keys($data);

        if(count($where)) {
            $itemsQuery = array();
            foreach($keysQuery as $item) {
                $itemsQuery[] = "`" . $item . "` = :" . $item;
            }
            $keysWhere = array_keys($where);
            $itemsWhere = array();
            foreach($keysWhere as $item) {
                $itemsWhere[] = "`" . $item . "` = :" . $item;
            }
            foreach($where as $name=>$value) {
                $data[$name] = $value;
            }
            $query = "UPDATE `" . $table . "` SET " . implode("`,`", $itemsQuery) . " WHERE " . implode(",", $itemsWhere);
        } else {
            $query = "INSERT INTO `" . $table . "` (`" . implode("`,`", $keysQuery) . "`) VALUES(:" . implode(", :", $keysQuery) . ")";
        }

        $this->req($query, (array) $data);

        return $this;
    }

    /**
     * Envoi d'une requete
     *
     * @param string $query
     * @param array $args
     * @return \PDOStatement
     */
    public function req($query, array $args = array())
    {
        $req = $this->prepare($query);

        if(count($args)) {
            $tmp = $args;
            if(!is_array(array_shift($tmp))) {
                $args = array($args);
            }
            foreach ($args as $arg) {
                $req->execute($arg);
                self::$count++;
            }
        } else {
            $req->execute();
        }

        return $req;
    }

    /**
     * Envoi d'un groupe de requetes
     *
     * @param array $queries
     * @return $this
     */
    public function bulk(array $queries)
    {
        try {
            $this->beginTransaction();

            foreach ($queries as $query) {
                $this->exec($query);
                self::$count++;
            }

            $this->commit();
        } catch (\sJo\Exception $Exception) {

            $this->rollback();
            $Exception::error(Lib\I18n::__('Drivers bulk rollback.'));
        }

        return $this;
    }

    /**
     * Resultat de la première colonne
     *
     * @param string $query
     * @param array $args
     * @param int $type
     * @return object
     */
    public function value($query, array $args = array(), $type = self::FETCH_OBJ)
    {
        return $this->req($query, $args)->fetchColumn(0);
    }


    /**
     * Resultat de la requete
     *
     * @param string $query
     * @param array $args
     * @param int $type
     * @return object
     */
    public function result($query, array $args = array(), $type = self::FETCH_OBJ)
    {
        return $this->req($query, $args)->fetch($type);
    }

    /**
     * Liste de résultats de la requete
     *
     * @param string $query
     * @param array $args
     * @param int $type
     * @return array
     */
    public function results($query, array $args = array(), $type = self::FETCH_OBJ)
    {
        return $this->req($query, $args)->fetchAll($type);
    }

    /**
     * Créé et retourne l'objet courrant
     *
     * @access public
     * @static
     * @param int $id
     * @return static
     */
    final public static function instance($id = 0)
    {
        if (isset(self::$auth[$id])) {
            if (!isset(self::$instances[$id])) {
                self::$instances[$id] = new static(self::$auth[$id]);
            }
            return self::$instances[$id];
        } else {
            \sJo\Exception::error(Lib\I18n::__('Drivers Unknow Auth ID %s.', $id));
        }
        return false;
    }

    /**
     * Déclaration des connexions
     *
     * @param array $data
     */
    final public static function auth(array $data)
    {
        self::$auth = $data;

        if(!is_array(array_shift($data))) {
            self::$auth = array(self::$auth);
        }
    }
}
