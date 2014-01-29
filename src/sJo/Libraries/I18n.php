<?php

/**
 * Gestion du multilanguage
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

/**
 * Gestion du multilanguage
 *
 * @package  sJo
 * @category Libraries
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 *
 * @method static __($message)
 * @method static n__($msgid1, $msgid2, $n)
 */
class I18n
{
    /**
     * Liste des répertoires de langue
     *
     * @access private
     * @var array
     */
    private static $directories = array();
    
    public function __construct()
    {
        defined('SJO_LOCALE') OR define('SJO_LOCALE', SJO_DEFAULT_LOCALE);

        $language = SJO_LOCALE . '.' . SJO_CHARSET;

        putenv('LANG=' . $language);
        putenv('LANGUAGE=' . $language);
        putenv('LC_ALL=' . $language);

        if(setlocale(LC_ALL, $language) != $language) {
            sJo\Exception::error('I18n locale ' . $language . ' do not exists.');
        }
    }

    /**
     * Chargement d'un répertoire de langue
     *
     * @param $domain
     * @param $directory
     * @return void
     */
    public function load($domain, $directory)
    {
        if(is_dir($directory)) {
            if(!in_array($directory, self::$directories)) {
                self::$directories[] = $directory;
                bindtextdomain($domain, $directory);
                bind_textdomain_codeset($domain, SJO_CHARSET);
            }
        } else {
            sJo\Exception::error('I18n directory ' . $directory . ' do not exists.');
        }
    }


    /**
     * @param $method
     * @param bool $args
     * @return bool|string
     */
    public static function __callStatic($method, $args = false)
    {
        if (preg_match("#^(__|n__)(.*)$#", $method, $match)) {

            $domain = $match[2] ? $match[2] : 'default';

            textdomain($domain);

            $message = false;
            $argsNum = 0;

            switch ($match[1]) {
                case '__':
                    $message = gettext($args[0]);
                    $argsNum = 1;
                    break;
                case 'n__':
                    $message = ngettext($args[0], $args[1], $args[2]);
                    $argsNum = 3;
                    break;
            }
            
            if($message) {
                return self::_replace($message, $args, $argsNum);
            }
        }
        return false;
    }

    public static function defaultLocale()
    {
        return SJO_DEFAULT_LOCALE;
    }

    public static function locale()
    {
        return SJO_LOCALE;
    }

    public static function country($locale = false)
    {
        if(!$locale) {
            $locale = SJO_LOCALE;
        }
        return strtolower(substr($locale, 3));
    }

    public static function language($locale = false)
    {
        if(!$locale) {
            $locale = SJO_LOCALE;
        }
        return strtolower(substr($locale, 0, 2));
    }

    public static function availableLanguages()
    {
        $languages = false;
        foreach(self::$directories as $directory) {
            $items = Path::listDirectories($directory);
            if($items) {
                foreach($items as $item) {
                    if(!isset($languages[$item->title])) {
                        $languages[$item->title] = (object) array(
                            'locale'    => $item->title,
                            'country'   => self::country($item->title),
                            'language'  => self::language($item->title)
                        );
                    }                       
                    $languages[$item->title]->paths[] = $item->path;
                }
            }
        }
        return $languages;
    }

    private static function _replace($message, $args, $start = 0)
    {
        if (count($args) === $start) {
            return $message;
        }
        return vsprintf($message, array_slice($args, $start));
    }
}
