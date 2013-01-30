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

abstract class Env
{
    /**
     * Gestion des différents tableaux de variables d'environement
     *
     * @method string  get(string $attr, string $default)
     * @method boolean getExists(string $attr)
     * @method void    getSet(string $attr, string $value)
     *
     * @method string  post(string $attr, string $default)
     * @method boolean postExists(string $attr)
     * @method void    postSet(string $attr, string $value)
     *
     * @method string  files(string $attr, string $default)
     * @method boolean filesExists(string $attr)
     * @method void    filesSet(string $attr, string $value)
     *
     * @method string  request(string $attr, string $default)
     * @method boolean requestExists(string $attr)
     * @method void    requestSet(string $attr, string $value)
     *
     * @method string  session(string $attr, string $default)
     * @method boolean sessionExists(string $attr)
     * @method void    sessionSet(string $attr, string $value)
     *
     * @method string  server(string $attr, string $default)
     * @method boolean serverExists(string $attr)
     * @method void    serverSet(string $attr, string $value)
     *
     * @method string  cookie(string $attr, string $default)
     * @method boolean cookieExists(string $attr)
     * @method void    cookieSet(string $attr, string $value)
     *
     * @method string  env(string $attr, string $default)
     * @method boolean envExists(string $attr)
     * @method void    envSet(string $attr, string $value)
     */
    public static function __callStatic($method, $args = false)
    {
        if (preg_match("#^([a-z]+?)(Set|Exists)?$#i", $method, $match)) {
            $var    = '_' . strtoupper($match[1]);
            $action = isset($match[2]) ? $match[2] : false;
            $attr   = self::g(0, false, $args);
            $value  = self::g(1, false, $args);
            global $$var;
            if (isset($$var)) {
                switch ($action) {
                    default:
                        return self::g($attr, $value, $$var);
                        break;
                    case 'Exists':
                        return self::e($attr, $$var);
                        break;
                    case 'Set':
                        self::s($attr, $value, $$var);
                        break;
                }
            }
        }
    }

    /**
     * Récupération d'une valeur
     *
     * @param string $attr    Clé du tableau
     * @param string $default Valeurs par défaut
     * @param string $var     Tableau de données
     * @return mixte
     */
    public static function g($attr, $default, &$var)
    {
        if (isset($var[$attr]) && !empty($var[$attr])) {
            return Str::stripslashes($var[$attr]);
        }
        return $default;
    }

    /**
     * Vérification de l'existance d'une valeur
     *
     * @param string $attr Clé du tableau
     * @param string $var  Tableau de données
     * @return boolean
     */
    public static function e($attr, &$var)
    {
        if (isset($var[$attr]) && !empty($var[$attr])) {
            return true;
        }
        return false;
    }

    /**
     * Définition d'une valeur
     *
     * @param string $attr    Clé du tableau
     * @param string $default Valeur
     * @param string $var     Tableau de données
     * @return void
     */
    public static function s($attr, $value, &$var)
    {
        $var[$attr] = $value;
    }
}
