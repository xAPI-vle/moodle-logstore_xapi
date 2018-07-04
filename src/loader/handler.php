<?php
namespace src\loader;

defined('MOODLE_INTERNAL') || die();

function handler(array $config, array $statements) {
    $loadername = $config['loader'];
    $load = "\src\loader\\$loadername\load";
    return $load($config, $statements);
}