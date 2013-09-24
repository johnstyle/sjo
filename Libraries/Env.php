<?php
/**
 * PHPTools
 *
 * PHP version 5
 *
 * @package  PHPTools
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */

namespace PHPTools\Libraries;

abstract class Env
{
	private static $opt = array();
	
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
    }

    /**
     * Récupération d'une valeur REQUEST (mlodifié)
     *
     * @param string $attr Clé du tableau
     * @param string $default Valeurs par défaut
     * @return mixte
     */
    public static function request($attr = false, $default = false)
    {
        return self::g($attr, self::g($attr, $default, $_GET), $_POST);
    }

    /**
     * Définition d'un cookie
     *
     * @param string $name  Nom
     * @param string $value Valeur
     * @return void
     */
    public static function cookieSet($name = false, $value = false, $expire = false, $path = '/', $domain = false, $secure = false, $httponly = false)
    {
        if($value) {
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
     * @param string $attr Clé du tableau
     * @param string $default Valeurs par défaut
     * @return mixte
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
     * @param mixte $attr Liste des options
     * @return mixte
     */
    public static function optSet($attr)
    {
    	if($attr) {
	    	$options = '';
	    	$longopts = array();
	    	if(!is_array($attr)) {
	    		$attr = array($attr);
	    	}
			foreach($attr as $item) {
				if(strlen(str_replace(':', '', $item)) == 1) {
					$options .= $item;
				} else {
					array_push($longopts, $item);
				}
			}
			self::$opt = array_merge(self::$opt, getopt($options, $longopts));
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
        if($attr !== false) {            
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
     * @param string $attr   Clé du tableau
     * @param boolean $empty Autorise ou non que la valeur soit vide
     * @param string $var    Tableau de données
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
