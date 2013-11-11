<?php
/**
 * phpTools
 *
 * PHP version 5
 *
 * @package  PHPTools
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/phpTools
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

if (defined('PHPTOOLS_TIMEZONE')) {
    date_default_timezone_set(PHPTOOLS_TIMEZONE);
}

/** Classes */
spl_autoload_register(
    function ($class) {
        $filename = str_replace('\\', '/', $class) . '.php';
        if (strstr($filename, 'PHPTools/')) {
            $filename = str_replace('PHPTools/', '', $filename);
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
foreach (\PHPTools\Libraries\Dir::getFiles(realpath(__DIR__) . '/Helpers') as $helpers) {
    include $helpers->path;
}

/** Locale */
//$PHPToolsI18n = new \PHPTools\Libraries\I18n();
//$PHPToolsI18n->load('default', realpath(__DIR__) . '/Locale');
