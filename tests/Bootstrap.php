<?php

define('ROOT', dirname(realpath(__DIR__)));

spl_autoload_register(function ($className) {
    $className = str_replace('\\', '/', $className);
    if(strstr($className, 'sJo/')) {
        $filename = ROOT . '/src/' . $className . '.php';
        if(file_exists($filename)) {
            require $filename;
        }
    }
});
