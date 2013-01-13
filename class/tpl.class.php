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

abstract class tpl
{
    /**
     * Inclusion du header
     *
     * @return void
     */
    public static function header()
    {
        self::inc(ROOT_TPL . '/header.php');
    }

    /**
     * Inclusion du footer
     *
     * @return void
     */
    public static function footer()
    {
        self::inc(ROOT_TPL . '/footer.php');
    }

    /**
     * Inclusion d'un fichier du template
     *
     * @return void
     */
    public static function inc($file)
    {
        if (file_exists($file)) {
            require $file;
        }
    }
}
