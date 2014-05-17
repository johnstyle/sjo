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

namespace sJo\Object;

trait Assign
{
    public function __construct ($items)
    {
        if ($items) {

            foreach ($items as $name=>$value) {

                if(property_exists($this, $name)) {

                    $this->{$name} = $value;
                }
            }
        }
    }
}
