<?php

namespace loader;

function handler(array $config, array $statements) {
    $loader_name = $config['loader'];
    $load = "\loader\\$loader_name\load";
    return $load($config, $statements);
}