<?php

/**
 * Gestion du multilanguage
 *
 * PHP version 5
 *
 * @package  PHPTools
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */

namespace PHPTools\Libraries;

/**
 * Gestion du multilanguage
 *
 * @package  PHPTools
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */
class I18n
{
    public function __construct()
    {
        putenv('LC_ALL=' . PHPTOOLS_LOCALE);
        setlocale(LC_ALL, PHPTOOLS_LOCALE);
    }

    public function load($domain, $directory)
    {
        bindtextdomain($domain, $directory);
        bind_textdomain_codeset($domain, PHPTOOLS_CHARSET);
        textdomain($domain);
    }

    public function __callStatic($method, $args = false)
    {
        if (preg_match("#^(__|n__|p__)(.+)$#", $args, $match)) {

            textdomain($match[2]);

            $message = false;
            $argsNum = false;

            switch ($match[1]) {
                case '__':
                    $message = call_user_func(array(self, 'getText'), $args);
                    $argsNum = 1;
                    break;
                case 'n__':
                    $message = call_user_func(array(self, 'ngetText'), $args);
                    $argsNum = 3;
                    break;
            }
            
            if($message) {
                return self::_replace($message, $args, $argsNum);
            }
        }
        return false;
    }

    public static function getText($message)
    {
        return gettext($message);
    }

    public static function ngetText($msgid1, $msgid2, $n)
    {
        return ngettext($msgid1, $msgid2, $n);
    }

    private static function _replace($message, $args, $start = 0)
    {
        if (func_num_args() === $start) {
            return $message;
        }
        $args = array_slice($args, $start);
        return vsprintf($message, is_array($args[0]) ? $args[0] : $args);
    }
}
