<?php

namespace loader\log;

function load(array $config, array $events) {
    echo(json_encode($events, JSON_PRETTY_PRINT)."\n");
}