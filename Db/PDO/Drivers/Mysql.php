<?php

/**
 * Base de données Mysql
 *
 * PHP version 5
 *
 * @package  PHPTools
 * @category Db\PDO\Drivers
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */

namespace PHPTools\Db\PDO\Drivers;

use \PHPTools\Libraries as Lib;

/**
 * Base de données Mysql
 *
 * @package  PHPTools
 * @category Db\PDO\Drivers
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */
class Mysql extends \PHPTools\Db\PDO\PDOCore
{
    /**
     * Constructeur
     *
     * @param array $auth
     * @return \PHPTools\Db\PDO\Drivers\Mysql
     */
    public function __construct(array $auth)
    {
        $auth = Lib\Arr::extend(array(
            'host' => 'localhost',
            'charset' => 'utf8'
        ), $auth);

        try {
            parent::__construct(
                'mysql:host=' . $auth['host'] . ';dbname=' . $auth['dbname'] . ';charset=' . $auth['charset'],
                $auth['login'],
                $auth['password']
            );
            $this->setAttribute(self::ATTR_ERRMODE, self::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }
}
