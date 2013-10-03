<?php

/**
 * Gestion des Vues
 *
 * PHP version 5
 *
 * @package  PHPTools
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */

namespace PHPTools;

/**
 * Gestion des Vues
 *
 * @package  PHPTools
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */
class View
{
   /**
    * Core references
    *
    * @var object
    */
    public static $Core;

   /**
    * Controller references
    *
    * @var object
    */
    public static $Controller;

   /**
    * Constructor
    *
    * @return void
    */
    public function __construct(&$instance)
    {
        self::$Controller =& $instance;
        self::$Core =& $instance->Core;
    }

    /**
     * Inclusion du header
     *
     * @return void
     */
    public static function header()
    {
        self::inc('header');
    }

    /**
     * Inclusion du footer
     *
     * @return void
     */
    public static function footer()
    {
        self::inc('footer');
    }

    /**
     * Inclusion d'un fichier du template
     *
     * @return void
     */
    public static function inc($filename, $vars = false)
    {
        $filename = str_replace('\\', '/', $filename);
        $path = PHPTOOLS_ROOT_VIEW . '/' . $filename . '.php';
        if (file_exists($path)) {
            if($vars) {
                foreach($vars as $var=>$value) {
                    global $$var;
                    $$var = $value;
                }
            }
            include $path;
            if($vars) {
                foreach($vars as $var=>$value) {
                    unset($$var);
                }
            }            
        }
    }

    public static function htmlClasses()
    {
        $classes =  'c-' . str_replace('/', '-', strtolower(CONTROLLER));
        if(METHOD) {
            $classes .=  ' m-' . strtolower(METHOD);
        }
        echo $classes;
    }

    public static function htmlStylesheet($root = './')
    {
        $filename = str_replace('\\', '-', strtolower(CONTROLLER)) . '.css';
        if(file_exists(PHPTOOLS_ROOT_PUBLIC_HTML . '/css/' . $filename)) {
            echo '<link href="' . $root . 'css/' . $filename . '" rel="stylesheet" media="screen" />';
        }
    }

    public static function htmlScript($root = './')
    {
        $filename = str_replace('\\', '-', strtolower(CONTROLLER)) . '.js';
        if(file_exists(PHPTOOLS_ROOT_PUBLIC_HTML . '/js/' . $filename)) {
            echo '<script type="text/javascript" src="' . $root . 'js/' . $filename . '"></script>';
        }
    }

    public static function basehref()
    {
        return PHPTOOLS_BASEHREF;
    }    
}
