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

namespace sJo\Http;

class Http
{
    public static function redirect($url = false)
    {
        http_response_code(301);
        header('Location:' . ($url ? $url : './'));
        exit;
    }
}
