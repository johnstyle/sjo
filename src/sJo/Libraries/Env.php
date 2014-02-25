<?php

/**
 * Variables d'environnement
 *
 * PHP version 5
 *
 * @package  sJo
 * @category Libraries
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\Libraries;

use sJo\Controller\Component\Session;

/**
 * Variables d'environnement
 *
 * @package  sJo
 * @category Libraries
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 *
 * @method static get(\string $attr = null, \string $default = null)
 * @method static getExists(\string $attr = null)
 * @method static getSet(\string $attr, \string $value = null)
 * @method static getHas(\string $attr, \string $value = null)
 *
 * @method static post(\string $attr = null, \string $default = null)
 * @method static postExists(\string $attr = null)
 * @method static postSet(\string $attr, \string $value = null)
 * @method static postHas(\string $attr, \string $value = null)
 *
 * @method static files(\string $attr = null, \string $default = null)
 * @method static filesExists(\string $attr = null)
 * @method static filesSet(\string $attr, \string $value = null)
 * @method static filesHas(\string $attr, \string $value = null)
 *
 * @method static requestExists(\string $attr = null)
 * @method static requestSet(\string $attr, \string $value = null)
 * @method static requestHas(\string $attr, \string $value = null)
 *
 * @method static session(\string $attr = null, \string $default = null)
 * @method static sessionExists(\string $attr = null)
 * @method static sessionHas(\string $attr, \string $value = null)
 *
 * @method static server(\string $attr = null, \string $default = null)
 * @method static serverExists(\string $attr = null)
 * @method static serverSet(\string $attr, \string $value = null)
 * @method static serverHas(\string $attr, \string $value = null)
 *
 * @method static cookie(\string $attr = null, \string $default = null)
 * @method static cookieExists(\string $attr = null)
 * @method static cookieHas(\string $attr = null, \string $value = null)
 *
 * @method static env(\string $attr = null, \string $default = null)
 * @method static envExists(\string $attr = null)
 * @method static envSet(\string $attr, \string $value = null)
 * @method static envHas(\string $attr, \string $value = null)
 */
abstract class Env
{
    private static $opt = array();

    /**
     * Gestion des différents tableaux de variables d'environement
     *
     */
    public static function __callStatic($method, $args = null)
    {
        if (preg_match("#^([a-z]+?)(Set|Exists|Has)?$#i", $method, $match)) {
            $var = '_' . strtoupper($match[1]);
            $action = isset($match[2]) ? $match[2] : null;
            $attr = self::g(0, null, $args);
            $value = self::g(1, null, $args);
            global $$var, $_SERVER;
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
                    case 'Has':
                        self::h($attr, $value, $$var);
                        break;
                }
            }
        }
        return false;
    }

    /**
     * Récupération d'une valeur REQUEST (mlodifié)
     *
     * @param bool|string $attr Clé du tableau
     * @param bool|string $default Valeurs par défaut
     * @return mixed
     */
    public static function request($attr = null, $default = null)
    {
        return self::g($attr, self::g($attr, $default, $_GET), $_POST);
    }

    /**
     * Définition d'une session
     *
     * @param mixed $attr Liste des options
     * @param $value
     * @return void
     */
    public static function sessionSet($attr, $value = null)
    {
        Session::write(function() use($attr, $value) {
            self::s($attr, $value, $_SESSION);
        });
    }

    /**
     * Définition d'un cookie
     *
     * @param bool|string $name Nom
     * @param bool|string $value Valeur
     * @param bool $expire
     * @param string $path
     * @param bool $domain
     * @param bool $secure
     * @param bool $httponly
     * @return void
     */
    public static function cookieSet(
        $name = false,
        $value = false,
        $expire = false,
        $path = '/',
        $domain = false,
        $secure = false,
        $httponly = false
    ) {
        if ($value) {
            setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
            $_COOKIE[$name] = $value;
        } else {
            setcookie($name, '', time() - 1000);
            setcookie($name, '', time() - 1000, '/');
            unset($_COOKIE[$name]);
        }
    }

    /**
     * Récupération d'une option phpcli
     *
     * @param bool|string $attr Clé du tableau
     * @param bool|string $default Valeurs par défaut
     * @return mixed
     */
    public static function opt($attr = null, $default = null)
    {
        return self::g($attr, $default, self::$opt);
    }

    /**
     * Vérification de l'existance d'une option phpcli
     *
     * @param string $attr Clé du tableau
     * @return boolean
     */
    public static function optExists($attr = null)
    {
        return self::e($attr, self::$opt);
    }

    /**
     * Définition d'options phpcli
     *
     * @param mixed $attr Liste des options
     * @return void
     */
    public static function optSet($attr)
    {
        if ($attr) {
            $options = '';
            $longopts = array();
            if (!is_array($attr)) {
                $attr = array($attr);
            }
            foreach ($attr as $item) {
                if (strlen(str_replace(':', '', $item)) == 1) {
                    $options .= $item;
                } else {
                    array_push($longopts, $item);
                }
            }
            self::$opt = Arr::extend(self::$opt, getopt($options, $longopts));
        }
    }

    /**
     * Récupération d'une valeur
     *
     * @param string $attr Clé du tableau
     * @param string $default Valeurs par défaut
     * @param string $var Tableau de données
     * @return mixed
     */
    public static function g($attr = null, $default = null, &$var)
    {
        if ($attr !== null) {
            $value = Arr::getTree($var, $attr);
            if ($value !== false && $value !== '') {
                return Str::stripslashes($value);
            }
        } else {
            return $var;
        }
        return $default;
    }

    /**
     * Vérification de l'existance d'une valeur
     *
     * @param string $attr Clé du tableau
     * @param string $var Tableau de données
     * @return boolean
     */
    public static function e($attr = null, &$var)
    {
        if (($attr === null && isset($var) & count($var))
            || ($attr !== null && isset($var[$attr]) && $var[$attr] !== null)) {
            return true;
        }
        return false;
    }

    /**
     * Définition d'une valeur
     *
     * @param string $attr Clé du tableau
     * @param $value
     * @param string $var Tableau de données
     * @return void
     */
    public static function s($attr, $value = null, &$var)
    {
        if ($value === null) {
            unset($var[$attr]);
        } else {
            $var[$attr] = $value;
        }
    }

    /**
     * Correspondance d'une valeur
     *
     * @param string $attr Clé du tableau
     * @param string $value Valeur à comparer
     * @param string $var Tableau de données
     * @return boolean
     */
    public static function h($attr, $value, &$var)
    {
        if (self::e($attr, $var)
            && self::g($attr) === $value) {
            return true;
        }
        return false;
    }
}
