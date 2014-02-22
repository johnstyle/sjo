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

namespace sJo\Libraries\Crypto;

class Crypto
{
    public static function salt()
    {
        return SJO_CRYPTO_SALT;
    }

    public static function md5($str)
    {
        return md5(self::salt() . $str);
    }
}
