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

    private $process_sections;

    private $scanner_mode;

    public function __construct($process_sections = null, $scanner_mode = INI_SCANNER_RAW)
    {
        $this->process_sections = $process_sections;
        $this->scanner_mode = $scanner_mode;
    }

    public static function load ($process_sections = null, $scanner_mode = INI_SCANNER_RAW)
    {
        return new self($process_sections, $scanner_mode);
    }

    public function get($args = false)
    {
        return Arr::getTree($this->data, $args);
    }

    public function gets()
    {
        return $this->data;
    }

    public function merge (array $data)
    {
        Arr::setTree($this->data, $data);

        return $this;
    }

    /**
     * Chargement d'un dossier contenant des fichiers ini
     *
     * @param $paths
     * @param bool $regexp
     * @param string $method
     * @return $this
     */
    public function path($paths, $regexp = false, $method = '{name}')
    {
        $regexp = $regexp ? $regexp : "^(.+)\.ini$";
        foreach (Arr::to($paths) as $path) {
            $files = Path::listFiles($path, $regexp);
            if ($files) {
                foreach ($files as $file) {
                    $name = str_replace('-', '_', $file->match[1]);
                    $name = $file->parentname == $name ? $name : str_replace('{name}', $name, $method);
                    $this->parseIniFile($file->path, $name);
                }
            }
        }
        return $this;
    }

    /**
     * Chargement d'un fichier ini
     *
     * @param $file
     * @return $this
     */
    public function file($file)
    {
        $this->parseIniFile($file);

        return $this;
    }

    /**
     * Transforme les valeurs en constantes
     */
    public function toDefine()
    {
        if ($this->data) {
            foreach ($this->data as $name => $value) {
                $name = strtoupper($name);
                if (!defined($name)) {
                    define($name, $value);
                }
            }
        }
    }

    private function parseIniFile ($file)
    {
        if(file_exists($file)) {
            $ini = parse_ini_file($file, $this->process_sections, $this->scanner_mode);
            $this->setValues($ini);
        }
    }

    private function setValues($ini, &$data = null)
    {
        if ($ini) {

            if(!isset($data)) {
                $data =& $this->data;
            }

            foreach ($ini as $name => $value) {

                if(is_array($value) || is_object($value)) {

                    if(!isset($data[$name])) {
                        $data[$name] = array();
                    }

                    $this->setValues($value, $data[$name]);

                } else {

                    $value = preg_replace_callback(
                        '#\$\{([A-Z0-9_]+)(\.([A-Z0-9_]+))?\}#i',
                        function ($match) {

                            $name = strtoupper($match[1] . (isset($match[3]) ? '_' . $match[3] : ''));

                            if(defined($name)) {

                                return constant($name);
                            } else {

                                if(isset($match[3])) {
                                    $data = Arr::getTree($this->data, $match[1]);
                                    $var = $match[3];
                                } else {
                                    $data = $this->data;
                                    $var = $match[1];
                                }

                                return Arr::getTree($data, $var);
                            }
                        },
                        $value
                    );

                    Arr::setTree($data, array($name => $value));
                }
            }
        }
    }
}
