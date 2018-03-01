<?php

namespace loader\log;

function load(array $config, array $statements) {
    echo(json_encode($statements, JSON_PRETTY_PRINT)."\n");
}