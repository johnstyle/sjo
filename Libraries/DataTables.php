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

class DataTables
{
    private $data;

    public static function columns($items)
    {
        $data = false;
        foreach($items as $item) {
            $data[] = array('sTitle' => $item);
        }
        return $data;
    }

    public function set($data)
    {
        $sEcho = 0;
        $aaData = array();
        if($data && count($data)) {
            foreach($data as $items) {
                $aData = array();
                foreach($items as $name=>$value) {
                    $aData[] = $value;
                }
                $aaData[] = $aData;
                if($sEcho < count($aData)) {
                    $sEcho = count($aData);
                }
            }
        }
        $this->data = array(
            'sEcho'                 => Env::request('sEcho'),
            'iTotalRecords'         => count($aaData),
            'iTotalDisplayRecords'  => count($aaData),
            'aaData'                => $aaData
        );
    }

    public function groupBy($field)
    {
        $this->data['groupBy'] = $field;
    }

    public function display()
    {
        header('Content-type:application/json; charset=utf-8');
        if(Env::get('callback')) {
            echo Env::get('callback') . '(' . json_encode($this->data) . ');';
        } else {
            echo json_encode($this->data);
        }
        exit;
    }
}