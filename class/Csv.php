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

class Csv
{
    protected $file;
    protected $handle;
    protected $rawHeader;
    protected $rawLine;
    protected $rawLines;
    protected $header;
    protected $line;
    protected $lines;
    protected $options;

    private static $separator = ';';
    private static $container = '"';
    private static $max_size = 1024;

    public function __construct ($options = array())
    {
        /** @formatter:off */
        $this->options = array_merge(array(
            'hasHeader'     => true,
            'lineStart'     => 0,
            'isArray'       => false,
            'orderby'       => false,
            'order'         => SORT_ASC,
            'limit'         => 0,
            'start'         => 0,
            'filter'        => false,
            'separator'     => self::$separator,
            'container'     => self::$container,
            'max_size'      => self::$max_size            
        ), $options);
        /** @formatter:on */

        self::$separator = $this->options['separator'];
        self::$container = $this->options['container'];
        self::$max_size = $this->options['max_size'];
    }

    /**
     * Destruction de la connexion avec le fichier
     *
     * @return void
     */
    public function __destruct ()
    {
        if ($this->handle) {
            fclose($this->handle);
        }
    }

    /**
     * Création du fichier
     *
     * @param string chemin du fichier
     * @return object
     */
    public function create ($file)
    {
        $this->file = $file;
        $this->delete();
        return $this->handle();
    }

    /**
     * Ouverture du fichier
     *
     * @param string chemin du fichier
     * @return object
     */
    public function open ($file)
    {
        $this->file = $file;
        return $this->handle();
    }

    /**
     * Rechargement du fichier avec récupération du header
     *
     * @return object
     */
    public function handle ()
    {
        if ($this->file) {
            if (!file_exists($this->file)) {
                touch($this->file);
            }
            if ($this->handle = fopen($this->file, 'r+')) {
                if ($this->options['hasHeader']) {
                    if ($this->options['lineStart']) {
                        for ($i = 0; $i < ($this->options['lineStart'] - 1); $i++) {
                            stream_get_line($this->handle, 0, "\n");
                        }
                    }
                    $this->rawHeader = stream_get_line($this->handle, 0, "\n");
                    $this->header = self::fromRaw($this->rawHeader);
                    if ($this->header) {
                        foreach ($this->header as $i => &$header) {
                            if (empty($header)) {
                                $header = 'column' . ($i + 1);
                            }
                        }
                    }
                }
            }
        }
        return $this->handle;
    }

    /**
     * Parcours de chaque ligne du fichier
     *
     * @return boolean
     */
    public function loop ()
    {
        if ($this->handle) {
            $feof = !feof($this->handle);
            $this->line = false;
            if ($this->rawLine = stream_get_line($this->handle, 0, "\n")) {
                return $feof;
            }
        }
        return false;
    }

    /**
     * Retourne la ligne courante
     *
     * @return object
     */
    public function getLine ()
    {
        return $this->line;
    }

    /**
     * Retourne le groupe de lignes courantes
     *
     * @return object
     */
    public function getLines ()
    {
        return $this->lines;
    }

    /**
     * Retourne la ligne courante au format CSV
     *
     * @return string
     */
    public function getRawLine ()
    {
        return $this->rawLine;
    }

    /**
     * Retourne le groupe de lignes courantes au format CSV
     *
     * @return string
     */
    public function getRawLines ()
    {
        return $this->rawLines;
    }

    /**
     * Transforme la ligne CSV en objet
     *
     * @return Csv
     */
    public function toObject ()
    {
        if ($this->handle) {
            $data = self::fromRaw($this->rawLine);
            if ($this->header) {
                foreach ($this->header as $i => $header) {
                    if ($this->options['isArray']) {
                        if (!$this->line) {
                            $this->line = array();
                        }
                        if (isset($data[$i])) {
                            $this->line[$header] = $data[$i];
                        } else {
                            $this->line[$header] = '';
                        }
                    } else {
                        if (!$this->line) {
                            $this->line = new \stdClass ();
                        }
                        if (isset($data[$i])) {
                            $this->line->{$header} = $data[$i];
                        } else {
                            $this->line->{$header} = '';
                        }
                    }
                }
            } else {
                foreach ($data as $i => $value) {
                    if ($this->options['isArray']) {
                        if (!$this->line) {
                            $this->line = array();
                        }
                        $this->line[$i] = $value;
                    } else {
                        if (!$this->line) {
                            $this->line = new \stdClass ();
                        }
                        $this->line->{$i} = $value;
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Ajoute plusieurs lignes de données
     *
     * @return void
     */
    public function addLines ($items)
    {
        $this->lines = false;
        $this->rawLines = false;
        if ($this->handle) {
            if ($items) {
                foreach ($items as $item) {
                    if ($this->addLine($item)) {
                        $this->lines[] = $this->line;
                        $this->rawLines .= self::toRaw($this->line);
                    }
                }
            }
        }
    }

    /**
     * Ajoute une ligne de données
     *
     * @return object
     */
    public function addLine ($item)
    {
        $this->line = false;
        $this->rawLine = false;
        if ($this->handle) {
            if ($item) {

                $item = (array)$item;

                /** Ajout des nouveaux éléments au header */
                if ($this->options['hasHeader']) {
                    $headerType = 'string';
                    if (!$this->header) {
                        $this->header = array();
                        foreach ($item as $name => $value) {
                            if (is_int($name)) {
                                $headerType = 'int';
                            }
                            if (!in_array($name, $this->header)) {
                                $this->header[] = $name;
                            }
                        }
                        $this->rawHeader = self::toRaw($this->header, false);
                    } else {
                        foreach ($item as $name => $value) {
                            if (is_int($name)) {
                                $headerType = 'int';
                            } elseif (!in_array($name, $this->header)) {
                                $this->header[] = $name;
                            }
                        }
                        $this->rawHeader = self::toRaw($this->header, false);
                    }
                    $this->prepend($this->rawHeader);

                    /** Constitution de la ligne */
                    $this->line = new \stdClass ();
                    foreach ($this->header as $i => $header) {
                        switch($headerType) {
                            case 'string' :
                                if (isset($item[$header])) {
                                    $this->line->{$header} = is_array($item[$header]) ? implode('\n', $item[$header]) : $item[$header];
                                } else {
                                    $this->line->{$header} = '';
                                }
                                break;
                            case 'int' :
                                if (isset($item[$i])) {
                                    $this->line->{$header} = is_array($item[$i]) ? implode('\n', $item[$i]) : $item[$i];
                                } else {
                                    $this->line->{$header} = '';
                                }
                                break;
                        }
                    }
                } else {
                    $this->line = $item;
                }
                $this->rawLine = self::toRaw($this->line);
                $this->append($this->rawLine);
            }
        }
        return $this->line;
    }

    public function prepend ($str)
    {
        fseek($this->handle, 0);
        fwrite($this->handle, $str . str_pad(" ", self::$max_size - strlen($str)) . "\n");
    }

    public function append ($str)
    {
        fseek($this->handle, 0, SEEK_END);
        fwrite($this->handle, $str);
    }

    /**
     * Affiche le CSV
     *
     * @return void
     */
    public function display ($filename)
    {
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv; charset: UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Pragma: public');
        readfile($this->file);
    }

    /**
     * Supprime le fichier
     *
     * @return void
     */
    public function delete ()
    {
        if (file_exists($this->file)) {
            unlink($this->file);
        }
    }

    /**
     * Insertion rapide dans un fichier de log
     */
    public static function log ($file, $line)
    {
        $csv = new self ();
        $csv->open($file);
        $csv->addLine($line);
    }

    /**
     * Converti une ligne CSV en tableau de données
     *
     * @return array
     */
    public static function fromRaw ($line)
    {
        $data = false;
        $line = trim($line);
        if (!empty($line)) {
            if (self::$container) {
                $first = strpos($line, self::$container) + 1;
                $last = strrpos($line, self::$container) - 1;
                $line = substr($line, $first, $last);
            }
            $items = explode(self::$container . self::$separator . self::$container, $line);
            foreach ($items as $item) {
                $data[] = $item;
            }
        }
        return $data;
    }

    /**
     * Converti un tableau de données en ligne CSV
     *
     * @return array
     */
    public static function toRaw ($line, $break = "\n")
    {
        $rawLine = false;
        if ($line) {
            foreach ($line as $key => $val) {
                $rawLine[] = self::$container . $val . self::$container;
            }
            $rawLine = implode(self::$separator, $rawLine) . $break;
        }
        return $rawLine;
    }
}
