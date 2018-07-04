<?php
namespace src\loader\log;

defined('MOODLE_INTERNAL') || die();

function load(array $config, array $statements) {
    echo(json_encode($statements, JSON_PRETTY_PRINT)."\n");
}