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

namespace PHPTools;

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
            if (isset($var[$attr]) && !empty($var[$attr])) {
                return Str::stripslashes($var[$attr]);
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
