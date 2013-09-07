<?php

namespace PHPTools;

class Alert
{
    private static $alerts;

    public function __construct()
    {
        session_start();

        if(Env::session('alerts')) {
            self::$alerts = json_decode(self::$alerts);
        }
    }

    public function __destruct()
    {
        Env::sessionSet('alerts', json_encode(self::$alerts));
    }

    public static function add($message, $type = 'danger')
    {
        self::$alerts[$type][] = $message;
    }

    public static function exists()
    {
        if(self::$alerts) {
            return true;
        }
        return false;
    }

    public static function display()
    {
        if(self::exists()) {
            foreach(self::$alerts as $type=>$alerts) {
                echo '<div class="alert alert-'.$type.'">';
                if(count($alerts) > 1) {
                    echo '<ol>';
                    foreach($alerts as $alert) {
                        echo '<li>' . $alert . '</li>';
                    }
                    echo '</ol>';
                } else {
                    foreach($alerts as $alert) {
                        echo '<p>' . $alert . '</p>';
                    }
                }
                echo '</div>';
            }
        }

        Env::sessionSet('alerts');
        self::$alerts = NULL;
    }
}
