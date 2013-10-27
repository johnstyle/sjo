<?php

/**
 * Base de données Drivers
 *
 * PHP version 5
 *
 * @package  PHPTools
 * @category Db
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */

namespace PHPTools\Db\PDO;

use \PHPTools\Libraries as Lib;

/**
 * Base de données Drivers
 *
 * @package  PHPTools
 * @category Db
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
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
     * Envoi d'une requete
     *
     * @param string $query
     * @param array $args
     * @return \PDOStatement
     */
    public function req($query, array $args = array())
    {
        $req = $this->prepare($query);

        if (count($args)) {
            if (!is_array($args[0])) {
                $args = array($args);
            }
        } else {
            $args = array(array());
        }

        foreach ($args as $arg) {
            $req->execute($arg);
            self::$count++;
        }

        return $req;
    }

    /**
     * Envoi d'un groupe de requetes
     *
     * @param array $queries
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
        } catch (\PHPTools\Exception $Exception) {

            $this->rollback();
            $Exception::error(Lib\I18n::__('Drivers bulk rollback.'));
        }
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
     * @internal param $void
     * @return \PDO $instance
     */
    final public static function instance($id = 0)
    {
        if (isset(self::$auth[$id])) {
            if (!isset(self::$instances[$id])) {
                self::$instances[$id] = new static(self::$auth[$id]);
            }
            return self::$instances[$id];
        } else {
            \PHPTools\Exception::error(Lib\I18n::__('Drivers Unknow Auth ID %s.', '<b>' . $id . '</b>'));
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
