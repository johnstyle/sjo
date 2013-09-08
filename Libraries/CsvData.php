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

namespace PHPTools\Libraries;

class CsvData extends Csv
{
    private $update;
    
    /**
     * Charge le fichier CSV dans un tableau
     * 
     * @return array
     */
    public function load()
    {
        $this->lines = false;
        while ($this->loop()) {
            $this->lines[] = $this->toObject()->getLine();
        }
        return $this->lines;
    }

    public function sort($orderby, $order=SORT_ASC){
        if($this->lines){
            Obj::sort($this->lines, $orderby, $order);
        }
        return $this->lines;
    }
    
    public function rsort($orderby, $order=SORT_DESC){
        $this->sort($orderby, $order);
    }
    
    public function find($key, $val, $return=false){
        $return = $return ? $return : $key;
        if($this->lines){
            foreach($this->lines as $line){
                if(isset($line->{$key}) && $line->{$key} == $val) return $line->{$return};
            }
        }
        return false;
    }
    
    public function groupBy($key){
        $values = false;
        if($this->lines){
            foreach($this->lines as $line){
                if(isset($line->{$key})) $values[] = $line->{$key};
            }
        }
        return $values;
    }
    
    public function limit($start, $limit){
        if($this->lines){
            $this->lines = array_slice($this->lines, $start, $limit);
        }
    }

    public function removeValue($key, $val){
        if($this->lines){
            foreach($this->lines as $i=>$line){
                if(isset($line->{$key}) && $line->{$key} == $val) $this->removeLine($i);
            }
        }
    }

    public function removeLine($i){
        if(isset($this->lines[$i])) {
            unset($this->lines[$i]);
            $this->update = true;
        }
    }

    public function save(){
        if($this->update) {
            if($this->lines){
                if($this->header) {
                    $str = self::toRaw($this->header, false);
                    $lines = $str . str_pad(" ", $this->max_size - strlen($str)) . "\n";
                } else {
                    $lines = false;
                }
                foreach($this->lines as $line) {
                    $lines .= self::toRaw($line);
                }
                file_put_contents($this->file, $lines, LOCK_EX);
            }else unlink($this->file);
        }
    }    
}
