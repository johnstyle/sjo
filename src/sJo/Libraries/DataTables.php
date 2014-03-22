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

use sJo\Request\Request;

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

    public function setData($data)
    {
        $aaData = array();
        if($data && count($data)) {
            foreach($data as $items) {
                $aData = array();
                foreach($items as $value) {
                    $aData[] = $value;
                }
                $aaData[] = $aData;
            }
        }

        $this->data['iTotalRecords']        = count($aaData);
        $this->data['iTotalDisplayRecords'] = count($aaData);
        $this->data['aaData']               = $aaData;
    }

    public function setGroups($groups)
    {
        $this->data['aaGroups'] = $groups;
    }    

    public function groupBy($field)
    {
        $this->data['groupBy'] = $field;
    }

    public function callback()
    {
        $this->data['sEcho'] = Request::env('REQUEST')->sEcho->val();

        return $this->data;
    }

    public function display()
    {
        header('Content-type:application/json; charset=utf-8');
        if(Request::env('REQUEST')->callback->exsist()) {
            echo Request::env('REQUEST')->callback->val() . '(' . json_encode($this->callback()) . ');';
        } else {
            echo json_encode($this->callback());
        }
        exit;
    }
}