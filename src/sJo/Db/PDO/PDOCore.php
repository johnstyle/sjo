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

use sJo\Exception\Exception;
use sJo\Module\Dependencies;
use sJo\Model;
use sJo\Libraries as Lib;

/**
 * Base de données Drivers
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
    use PDOQuery;
    use Model\Singleton {
        Model\Singleton::getInstance as SingletonGetInstance;
    }

    /**
     * @var array Liste des connexions
     * @access private
     */
    private static $auth = array();

    /**
     * @var int Total Queries
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
    public function req($query, array $args = null)
    {
        $req = $this->prepare($query);

        if($args) {
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
        } catch (Exception $Exception) {

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
     * @return string
     */
    public function fetchColumn($query, array $args = null)
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
    public function fetch($query, array $args = null, $type = self::FETCH_OBJ)
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
    public function fetchAll($query, array $args = null, $type = self::FETCH_OBJ)
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
    public static function getInstance($id = 0)
    {
        if (isset(self::$auth[$id])) {
            return static::SingletonGetInstance(self::$auth[$id], $id);
        } else {
            Exception::error(Lib\I18n::__('Drivers Unknow Auth ID %s.', $id));
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
        Dependencies::register(get_called_class());

        self::$auth = $data;

        if(!is_array(array_shift($data))) {
            self::$auth = array(self::$auth);
        }
    }
}
