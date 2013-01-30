<?php
/**
 * phpTools
 *
 * PHP version 5
 *
 * @package  phpTools
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/phpTools
 */

namespace phpTools;

abstract class Db
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
        $request = mysql_query($query, self::$resource) or die("Erreur SQL : " . mysql_error() . "\n" . $query . "\n");
        if ($type) {
            if ($request) {
                switch($type) {
                    case 'gets':
                        while ($item = mysql_fetch_object($request))
                            $results[] = $item;
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
        if ($type)
            return $results;
        else
            return $request;
    }

    private static function connect()
    {
        if (!self::$resource) {
            self::$resource = mysql_connect(PHPTOOLS_DB_HOST, PHPTOOLS_DB_USER, PHPTOOLS_DB_PWD) or die("Fatal ERROR SERVER : Check the connection script.\n");
            mysql_select_db(PHPTOOLS_DB_BASE, self::$resource) or die("Fatal ERROR DATABASE : Check the connection script.\n");
            switch(PHPTOOLS_CHARSET) {
                case 'UTF-8':
                    mysql_query('SET NAMES "utf8"', self::$resource) or die("Erreur SQL : " . mysql_error() . "\n");
                    break;
            }
        }
    }

}
