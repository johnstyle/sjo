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
    private $file;

    private $handle;
    private $rawHeader;
    private $rawLine;

    private $header;
    private $line;

    private $options;

    const SEPARATOR = ';';
    const CONTAINER = '"';
    const MAX_SIZE = 1024;

    public function __construct($options = array())
    {
        $this->options = array_merge(array(
            'hasHeader' => true,
            'isArray' => false,
            'orderby' => false,
            'order' => SORT_ASC,
            'limit' => 0,
            'start' => 0,
            'filter' => false
        ), $options);
    }

    public function __destruct()
    {
        if ($this->handle) {
            fclose($this->handle);
        }
    }

    public function loadFile($file)
    {
        $this->file = $file;
        if (!$this->handle) {
            $this->reload();
        }
    }

    public function reload()
    {
        if ($this->file) {
            if (!file_exists($this->file)) {
                touch($this->file);
            }
            $this->handle = fopen($this->file, 'r+');
            if($this->handle) {
                if ($this->options['hasHeader']) {
                    $this->rawHeader = stream_get_line($this->handle, 0, "\n");
                    $this->header = self::fromRaw($this->rawHeader);
                }
            }
        }
    }

    public function loop()
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

    public function getLine()
    {
        return $this->line;
    }

    public function getRawLine()
    {
        return $this->rawLine;
    }

    public function toObject()
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
                            $this->line = new \stdClass();
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
                            $this->line = new \stdClass();
                        }
                        $this->line->{$i} = $value;
                    }
                }
            }
        }

        return $this;
    }

    public function addLines($items)
    {
        if ($this->handle) {
            if ($items) {
                foreach ($items as $item) {
                    $this->addLine($item);
                }
            }
        }
    }

    public function addLine($item)
    {
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
                        $this->rawHeader = self::toRaw($this->header);
                    } else {
                        foreach ($item as $name => $value) {
                            if (is_int($name)) {
                                $headerType = 'int';
                            } elseif (!in_array($name, $this->header)) {
                                $this->header[] = $name;
                            }
                        }
                        $this->rawHeader = self::toRaw($this->header);
                    }
    
                    /** Constitution de la ligne */
                    $this->line = new \stdClass();
                    foreach ($this->header as $i => $header) {
                        switch($headerType) {
                            case 'string':
                                if (isset($item[$header])) {
                                    $this->line->{$header} = is_array($item[$header]) ? implode('\n', $item[$header]) : $item[$header];
                                } else {
                                    $this->line->{$header} = '';
                                }
                                break;
                            case 'int':
                                if (isset($item[$i])) {
                                    $this->line->{$header} = is_array($item[$i]) ? implode('\n', $item[$i]) : $item[$i];
                                } else {
                                    $this->line->{$header} = '';
                                }
                                break;
                        }
                    }
    
                    fseek($this->handle, 0);
                    fwrite($this->handle, $this->rawHeader . str_pad(" ", self::MAX_SIZE - strlen($this->rawHeader)) . "\n");
                } else {
                    $this->line = $item;
                }
    
                $this->rawLine = self::toRaw($this->line);
                fseek($this->handle, 0, SEEK_END);
                fwrite($this->handle, $this->rawLine);
            }
        }
    }

    public static function fromRaw($line)
    {
        $data = false;
        $line = trim($line);
        if (!empty($line)) {
            if (self::CONTAINER) {
                $first = strpos($line, self::CONTAINER) + 1;
                $last = strrpos($line, self::CONTAINER) - 1;
                $line = substr($line, $first, $last);
            }
            $items = explode(self::CONTAINER . self::SEPARATOR . self::CONTAINER, $line);
            foreach ($items as $item) {
                $data[] = $item;
            }
        }
        return $data;
    }

    public static function toRaw($line)
    {
        $rawLine = false;
        if ($line) {
            foreach ($line as $key => $val) {
                $rawLine[] = self::CONTAINER . $val . self::CONTAINER;
            }
            $rawLine = implode(self::SEPARATOR, $rawLine) . "\n";
        }
        return $rawLine;
    }
}
