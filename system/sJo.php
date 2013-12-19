<?php
/**
 * sJo
 *
 * PHP version 5
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

/** Settings */
if (file_exists(realpath(__DIR__) . '/settings.ini')) {
    $settings = parse_ini_file(realpath(__DIR__) . '/settings.ini', null, INI_SCANNER_RAW);
    if ($settings) {
        foreach ($settings as $name => $value) {
            $value = preg_replace_callback(
                "#\\$\{([A-Z0-9_]+)\}#",
                function ($match) {
                    return constant($match[1]);
                },
                $value
            );
            if (!defined($name)) {
                define($name, $value);
            }
        }
    }
}

if (defined('SJO_TIMEZONE')) {
    date_default_timezone_set(SJO_TIMEZONE);
}

/** Classes */
spl_autoload_register(
    function ($class) {
        $filename = str_replace('\\', '/', $class) . '.php';
        if (strstr($filename, 'sJo/')) {
            $filename = str_replace('sJo/', '', $filename);
            if (!strstr($filename, '/')) {
                $file = realpath(__DIR__) . '/Core/' . $filename;
                if (file_exists($file)) {
                    include $file;
                }
            } else {
                $file = realpath(__DIR__) . '/' . $filename;
                if (file_exists($file)) {
                    include $file;
                }
            }
        }
    }
);

/** Helpers */
foreach (\sJo\Libraries\Dir::getFiles(realpath(__DIR__) . '/Helpers') as $helpers) {
    include $helpers->path;
}

/** Locale */
//$sJo_I18n = new \sJo\Libraries\I18n();
//$sJo_I18n->load('default', dirname(dirname(__DIR__)) . '/locale');
