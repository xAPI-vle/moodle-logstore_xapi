<?php

namespace src\loader;

function handler(array $config, array $statements) {
    $loader_name = $config['loader'];
    $load = "\src\loader\\$loader_name\load";
    return $load($config, $statements);
}