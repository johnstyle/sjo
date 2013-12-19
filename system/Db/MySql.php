<?php

/**
 * Base de données MySql
 *
 * PHP version 5
 *
 * @package  sJo
 * @category Db
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\Db;

/**
 * Base de données MySql
 *
 * @deprecated Préférer l'utilisation de la classe \sJo\Db\PDO\Mysql();
 * @todo Supprimer cette clase obsolète
 *
 * @package  sJo
 * @category Db
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
abstract class MySql
{
    static public $resource;
    static public $hits = 0;

    public static function s($str)
    {
        return addslashes(stripslashes($str));
    }

    public static function gets($query)
    {
        return self::query($query, 'gets');
    }

    public static function get($query)
    {
        return self::query($query, 'get');
    }

    public static function str($query)
    {
        return self::query($query, 'var');
    }

    public static function req($query)
    {
        if (self::query($query)) {
            return mysql_insert_id(self::$resource);
        }
        return false;
    }

    private static function query($query, $type = false)
    {
        self::connect();
        $results = false;
        $request = mysql_query($query, self::$resource)
        or die("Erreur SQL : " . mysql_error() . "\n" . $query . "\n");
        if ($type) {
            if ($request) {
                switch ($type) {
                    case 'gets':
                        while ($item = mysql_fetch_object($request)) {
                            $results[] = $item;
                        }
                        break;
                    case 'get':
                        $results = mysql_fetch_object($request);
                        break;
                    case 'var':
                        $results = mysql_result($request, 0);
                        break;
                }
            }
        }
        self::$hits++;
        if ($type) {
            return $results;
        } else {
            return $request;
        }
    }

    private static function connect()
    {
        if (!self::$resource) {
            self::$resource = mysql_connect(SJO_DB_HOST, SJO_DB_USER, SJO_DB_PWD)
            or die("Fatal ERROR SERVER : Check the connection script.\n");
            mysql_select_db(SJO_DB_BASE, self::$resource)
            or die("Fatal ERROR DATABASE : Check the connection script.\n");
            switch (SJO_CHARSET) {
                case 'UTF-8':
                    mysql_query('SET NAMES "utf8"', self::$resource) or die("Erreur SQL : " . mysql_error() . "\n");
                    break;
            }
        }
    }
}
