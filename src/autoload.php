<?php

namespace src;

defined('MOODLE_INTERNAL') || die();

function autoload_src() {
    $directory = new \RecursiveDirectoryIterator(__DIR__);
    $iterator = new \RecursiveIteratorIterator($directory);
    $files = [];
    foreach ($iterator as $info) {
        $pathname = $info->getPathname();
        if (substr($pathname, -4) === '.php' && $pathname != __FILE__) {
            require_once($pathname);
        }
    }
}

autoload_src();
