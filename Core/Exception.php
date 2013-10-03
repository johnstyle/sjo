<?php

/**
 * Gestion des Exception
 *
 * PHP version 5
 *
 * @package  PHPTools
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */

namespace PHPTools;

/**
 * Gestion des Exception
 *
 * @package  PHPTools
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */
class Exception extends \Exception
{
    public function __construct($msg = NULL, $code = 0) 
    {
        parent::__construct($msg, $code);
    }

    public function showError()
    {
        die('<div style="color:red">' . $this->getMessage() . '</div>');
    }

    public static function error($msg = NULL, $code = 0) 
    {
        $Exception = new self($msg, $code);
        $Exception->showError();
    }    
}
