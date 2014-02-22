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

namespace sJo\View;

use sJo\Controller\Controller;
use sJo\Loader\Router;

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
    public static $controller;

    /**
     * Constructor
     *
     * @param $instance
     * @return \sJo\View\View
     */
    public function __construct(Controller &$instance)
    {
        self::$controller =& $instance;
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


    public static function display()
    {
        require Router::$viewFile;
    }

    /**
     * Inclusion d'un fichier du template
     *
     * @param $filename
     * @param array $vars
     * @return void
     */
    public static function inc($filename, array $vars = null)
    {
        $file = Router::$viewRoot . '/' . $filename . '.php';

        if (file_exists($file)) {
            if($vars) {
                foreach($vars as $var=>$value) {
                    global $$var;
                    $$var = $value;
                }
            }

            require $file;

            if($vars) {
                foreach($vars as $var=>$value) {
                    unset($$var);
                }
            }            
        }
    }

    public static function htmlClasses()
    {
        $classes =  'c-' . str_replace('/', '-', strtolower(Router::$controller));
        if(Router::$module) {
            $classes .=  ' m-' . strtolower(Router::$module);
        }
        echo $classes;
    }

    public static function htmlStylesheet($root = './')
    {
        $filename = str_replace('\\', '/', strtolower(Router::$controller)) . '.css';
        if(file_exists(SJO_ROOT_PUBLIC_HTML . '/css/' . $filename)) {
            echo '<link href="' . $root . 'css/' . $filename . '" rel="stylesheet" media="screen" />';
        }
    }

    public static function htmlScript($root = './')
    {
        $filename = str_replace('\\', '/', strtolower(Router::$controller)) . '.js';
        if(file_exists(SJO_ROOT_PUBLIC_HTML . '/js/' . $filename)) {
            echo '<script type="text/javascript" src="' . $root . 'js/' . $filename . '"></script>';
        }
    }

    public static function basehref()
    {
        return SJO_BASEHREF;
    }    
}
