<?php

namespace src;

define('MOODLE_INTERNAL', 1);

function autoload_src() {
    $directory = new \RecursiveDirectoryIterator(__DIR__);
    $iterator = new \RecursiveIteratorIterator($directory);
    $files = [];
    foreach ($iterator as $info) {
        $path_name = $info->getPathname();
        if (substr($path_name, -4) === '.php' && $path_name != __FILE__) {
            require_once($path_name);
        }
    }
}

autoload_src();