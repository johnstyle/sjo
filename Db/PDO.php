<?php

/**
 * Base de données PDO
 *
 * PHP version 5
 *
 * @package  PHPTools
 * @category Db
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */

namespace PHPTools\Db;

use \PHPTools\Libraries as Lib;

/**
 * Base de données PDO
 *
 * @package  PHPTools
 * @category Db
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */
class PDO extends \PDO
{

    /**
     * Instances de la classe PDO
     *
     * @var array
     * @access private
     */
    private static $instances = array();

    private static $auth = array();
    private static $count = 0;
    private static $type = 'mysql';

    /**
     * Constructeur
     *
     * @param $host
     * @param $dbname
     * @param $username
     * @param $password
     * @return \PHPTools\Db\PDO
     */
    public function __construct($host, $dbname, $username, $password)
    {
        try {
            parent::__construct(self::$type . ':host=' . $host . ';dbname=' . $dbname, $username, $password);
            $this->setAttribute(self::ATTR_ERRMODE, self::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    public function req($query, $args = array())
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
            $Exception::error(Lib\I18n::__('PDO bulk rollback.'));
        }
    }

    public function result($query, $args = array(), $type = self::FETCH_OBJ)
    {
        return $this->req($query, $args)->fetch($type);
    }

    public function results($query, $args = array(), $type = self::FETCH_OBJ)
    {
        return $this->req($query, $args)->fetchAll($type);
    }

    /**
     * Crée et retourne l'objet PDO
     *
     * @access public
     * @static
     * @param int $id
     * @internal param $void
     * @return \PDO $instance
     */
    public static function instance($id = 0)
    {
        if (isset(self::$auth[$id])) {
            if (!isset(self::$instances[$id])) {
                self::$instances[$id] = new self(self::$auth[$id][0], self::$auth[$id][1], self::$auth[$id][2], self::$auth[$id][3]);
            }
            return self::$instances[$id];
        } else {
            \PHPTools\Exception::error(Lib\I18n::__('PDO Unknow Auth ID %s.', '<b>' . $id . '</b>'));
        }
        return false;
    }

    public static function auth($host, $dbname = false, $username = false, $password = false)
    {
        if (is_array($host)) {
            self::$auth = $host;
        } else {
            self::$auth = array(
                array($host, $dbname, $username, $password)
            );
        }
    }
}
