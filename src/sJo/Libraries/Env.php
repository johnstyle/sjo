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
 * @method static get(\string $attr = false, \string $default = false)
 * @method static getExists(\string $attr)
 * @method static getSet(\string $attr, \string $value)
 *
 * @method static post(\string $attr = false, \string $default = false)
 * @method static postExists(\string $attr)
 * @method static postSet(\string $attr, \string $value)
 *
 * @method static files(\string $attr = false, \string $default = false)
 * @method static filesExists(\string $attr)
 * @method static filesSet(\string $attr, \string $value)
 *
 * @method static requestExists(\string $attr)
 * @method static requestSet(\string $attr, \string $value)
 *
 * @method static session(\string $attr = false, \string $default = false)
 * @method static sessionExists(\string $attr)
 *
 * @method static server(\string $attr = false, \string $default = false)
 * @method static serverExists(\string $attr)
 * @method static serverSet(\string $attr, \string $value)
 *
 * @method static cookie(\string $attr = false, \string $default = false)
 * @method static cookieExists(\string $attr)
 *
 * @method static env(\string $attr = false, \string $default = false)
 * @method static envExists(\string $attr)
 * @method static envSet(\string $attr, \string $value)
 */
abstract class Env
{
    private static $opt = array();

    /**
     * Gestion des différents tableaux de variables d'environement
     *
     */
    public static function __callStatic($method, $args = false)
    {
        if (preg_match("#^([a-z]+?)(Set|Exists)?$#i", $method, $match)) {
            $var = '_' . strtoupper($match[1]);
            $action = isset($match[2]) ? $match[2] : false;
            $attr = self::g(0, false, $args);
            $value = self::g(1, false, $args);
            global $$var, $_SERVER;
            if (isset($$var)) {
                switch ($action) {
                    default:
                        return self::g($attr, $value, $$var);
                        break;
                    case 'Exists':
                        return self::e($attr, $value, $$var);
                        break;
                    case 'Set':
                        self::s($attr, $value, $$var);
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
    public static function request($attr = false, $default = false)
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
    public static function sessionSet($attr, $value = false)
    {
        Session::write(function() use($attr, $value) {
            $_SESSION[$attr] = $value;
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
    public static function opt($attr = false, $default = false)
    {
        return self::g($attr, $default, self::$opt);
    }

    /**
     * Vérification de l'existance d'une option phpcli
     *
     * @param string $attr Clé du tableau
     * @param boolean $empty Autorise ou non que la valeur soit vide
     * @return boolean
     */
    public static function optExists($attr, $empty = false)
    {
        return self::e($attr, $empty, self::$opt);
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
    public static function g($attr, $default, &$var)
    {
        if ($attr !== false) {
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
     * @param boolean $empty Autorise ou non que la valeur soit vide
     * @param string $var Tableau de données
     * @return boolean
     */
    public static function e($attr, $empty = false, &$var)
    {
        if (isset($var[$attr]) && ((!$empty && !empty($var[$attr])) || $empty)) {
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
     * @internal param string $default Valeur
     * @return void
     */
    public static function s($attr, $value, &$var)
    {
        $var[$attr] = $value;
    }
}
