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

abstract class Tpl
{
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
    public static function inc($filename)
    {
        $path = PHPTOOLS_ROOT_TPL . '/' . $filename . '.php';
        if (file_exists($path)) {
            require $path;
        }
    }
}
