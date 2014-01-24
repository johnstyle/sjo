<?php

/**
 * Gestion des Vues
 *
 * PHP version 5
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\Core;

/**
 * Gestion des Vues
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
final class View
{
    /**
     * Model references
     *
     * @var Controller
     */
    public static $Controller;

    /**
     * Core references
     *
     * @var object
     */
    public static $Core;

    /**
     * Logger references
     *
     * @var Logger
     */
    public static $Logger;

    /**
     * Module references
     *
     * @var Module
     */
    public static $Module;

    /**
     * Constructor
     *
     * @param $instance
     * @return \sJo\Core\View
     */
    public function __construct(&$instance)
    {
        self::$Controller =& $instance;
        self::$Core =& $instance->Core;
        self::$Logger =& $instance->Logger;
        self::$Module =& $instance->Module;
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


    public static function load()
    {
        if(file_exists(Loader::$viewFile)) {
            include Loader::$viewFile;
        }
    }


    /**
     * Inclusion d'un fichier du template
     *
     * @param $filename
     * @param bool $vars
     * @return void
     */
    public static function inc($filename, $vars = false)
    {
        $file = SJO_ROOT_VIEW . '/' . $filename . '.php';

        if (file_exists($file)) {
            if($vars) {
                foreach($vars as $var=>$value) {
                    global $$var;
                    $$var = $value;
                }
            }

            include $file;

            if($vars) {
                foreach($vars as $var=>$value) {
                    unset($$var);
                }
            }            
        }
    }

    public static function htmlClasses()
    {
        $classes =  'c-' . str_replace('/', '-', strtolower(Loader::$controller));
        if(Loader::$module) {
            $classes .=  ' m-' . strtolower(Loader::$module);
        }
        echo $classes;
    }

    public static function htmlStylesheet($root = './')
    {
        $filename = str_replace('\\', '/', strtolower(Loader::$controller)) . '.css';
        if(file_exists(SJO_ROOT_PUBLIC_HTML . '/css/' . $filename)) {
            echo '<link href="' . $root . 'css/' . $filename . '" rel="stylesheet" media="screen" />';
        }
    }

    public static function htmlScript($root = './')
    {
        $filename = str_replace('\\', '/', strtolower(Loader::$controller)) . '.js';
        if(file_exists(SJO_ROOT_PUBLIC_HTML . '/js/' . $filename)) {
            echo '<script type="text/javascript" src="' . $root . 'js/' . $filename . '"></script>';
        }
    }

    public static function basehref()
    {
        return SJO_BASEHREF;
    }    
}
