<?php
/**
 * sJo
 *
 * PHP version 5
 *
 * @package  sJo
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\Libraries;

class Ini
{
    /**
     * Tableau des valeurs des fichiers .ini
     *
     * @access private
     */
    private $data = array();

    public function __construct(&$default = array())
    {
        $this->data =& $default;
    }

    public function get($args = false)
    {
        return Arr::getTree($this->data, $args);
    }

    public function gets()
    {
        return $this->data;
    }

    /**
     * Chargement des fichiers .ini
     *
     * @param $paths
     * @param bool $regexp
     * @param string $method
     * @return object
     */
    public function loadPath($paths, $regexp = false, $method = '<name>')
    {
        $regexp = $regexp ? $regexp : "^(.+)\.ini$";
        foreach (Arr::to($paths) as $path) {
            $files = Dir::getFiles($path, $regexp);
            if ($files) {
                foreach ($files as $file) {
                    $name = str_replace('-', '_', $file->match[1]);
                    $name = $file->parentname == $name ? $name : str_replace('<name>', $name, $method);
                    $this->loadFile($file->path, $name);
                }
            }
        }
        return $this->data;
    }

    public function loadFile($file, $name = false)
    {
        if ($name) {
            if (!isset($this->data[$name])) {
                $this->data[$name] = array();
            }
            Arr::setTree($this->data[$name], parse_ini_file($file, true));
            return $this->data[$name];
        } else {
            Arr::setTree($this->data, parse_ini_file($file, true));
            return $this->data;
        }
    }
}
