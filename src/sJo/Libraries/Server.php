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

abstract class Server
{
    public static function cpuInfo ()
    {
        exec('cat /proc/cpuinfo | grep processor | wc -l', $cpu);
        exec('awk "{print $1}" /proc/loadavg', $avg);
        return (float)round($avg[0] / $cpu[0], 2) * 100;
    }

    public static function memInfo ()
    {
        exec('cat /proc/meminfo | grep MemTotal | sed "s/[^[:digit:]]//g"', $total);
        exec('cat /proc/meminfo | grep MemFree | sed "s/[^[:digit:]]//g"', $free);
        return (float)round(($total[0] - $free[0]) / $total[0], 2) * 100;
    }

    public static function getVersion ($type)
    {
        $version = null;

        switch($type) {
            case 'git' :
                $version = 'git --version';
                break;
            case 'mysql' :
                $version = 'mysql --version | cut -d"," -f1';
                break;
        }

        if ($version) {
            exec($version, $response);
            return (string)$response[0];
        }

        return false;
    }

    public static function getProcess ($type)
    {
        $grep = null;

        switch($type) {
            case 'php' :
                $grep = '[.]php';
                break;
            case 'apache' :
                $grep = 'apache[2]';
                break;
            case 'rsync' :
                $grep = 'rsync-bots[.sh]';
                break;
            case 'ssh' :
                $grep = 'ssh[d]:';
                break;
        }

        if ($grep) {
            exec('ps -ef | grep "' . $grep . '" | wc -l', $total);
            return (int)$total[0];
        }

        return false;
    }
}
