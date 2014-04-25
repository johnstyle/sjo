<?php

namespace sJo\Helpers;

use sJo\File\File;

function Autoload($path)
{
	spl_autoload_register(
	    function ($className) use ($path) {
	        $className = str_replace('\\', '/', $className);
            $filename = $path . '/' . $className . '.php';
	        File::__include($filename);
	    }
	);
}
