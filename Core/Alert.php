<?php

namespace PHPTools;

class Alert
{
    private $alerts;

    public function __construct()
    {
        Libraries\Env::sessionStart();

        if(Libraries\Env::session('alerts')) {
            $this->alerts = json_decode($this->alerts);
        }
    }

    public function __destruct()
    {
        Libraries\Env::sessionSet('alerts', json_encode($this->alerts));
    }

    public function add($message, $type = 'danger')
    {
        $this->alerts[$type][] = $message;
    }

    public function exists()
    {
        if($this->alerts) {
            return true;
        }
        return false;
    }

    public function display()
    {
        if($this->exists()) {
            foreach($this->alerts as $type=>$alerts) {
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

        Libraries\Env::sessionSet('alerts');
        $this->alerts = NULL;
    }
}
