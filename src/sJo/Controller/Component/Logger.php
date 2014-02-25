<?php

namespace sJo\Controller\Component;

use Psr\Log\AbstractLogger;
use sJo\Libraries as Lib;

/**
 * This Logger can be used to avoid conditional log calls
 *
 * Logging should always be optional, and if no logger is provided to your
 * library creating a NullLogger instance to have something to throw logs at
 * is a good way to avoid littering your code with `if ($this->logger) { }`
 * blocks.
 */
class Logger extends AbstractLogger
{
    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        Lib\File::append(SJO_ROOT_LOG . '/app.' . $level . '.log',
            date('Y-m-d H:i:s') . "\t" .
            Lib\Env::server('REQUEST_URI') . "\t" .
            self::interpolate($message, $context) . "\n");
    }

    public static function interpolate($message, array $context = array())
    {
        $replace = array();
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }

        return strtr($message, $replace);
    }
}
