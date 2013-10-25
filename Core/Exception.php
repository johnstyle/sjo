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
    /**
     * Constructeur
     *
     * @param null $msg
     * @param int $code
     * @return \PHPTools\Exception
     */
    public function __construct($msg = null, $code = 0)
    {
        parent::__construct($msg, $code);
    }

    public function showError()
    {
        die('<div style="color:red">' . $this->getMessage() . '</div>');
    }

    public static function error($msg = null, $code = 0)
    {
        $Exception = new self($msg, $code);
        $Exception->showError();
    }
}
