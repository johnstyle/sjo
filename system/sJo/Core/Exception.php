<?php

/**
 * Gestion des Exception
 *
 * PHP version 5
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\Core;

use sJo\Libraries\I18n;

/**
 * Gestion des Exception
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
class Exception extends \Exception
{
    /**
     * Constructeur
     *
     * @param null $msg
     * @param int $code
     * @return \sJo\Core\Exception
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

    public static function ErrorDocument($controller, $msg = false, $code = 0)
    {
        new self($msg, $code);

        $Loader = new Loader('ErrorDocument\\' . $controller);
        $Loader->init();
        $Loader->instance()->message = $msg;
        $Loader->display();
        exit;
    }
}
