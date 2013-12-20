<?php

/**
 * Base de données Mysql
 *
 * PHP version 5
 *
 * @package  sJo
 * @category Db\PDO\Drivers
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\Db\PDO\Drivers;

use \sJo\Libraries as Lib;

/**
 * Base de données Mysql
 *
 * @package  sJo
 * @category Db\PDO\Drivers
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
class Mysql extends \sJo\Db\PDO\PDOCore
{
    /**
     * Constructeur
     *
     * @param array $auth
     * @return \sJo\Db\PDO\Drivers\Mysql
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
