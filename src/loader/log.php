<?php

namespace loader;

function load_to_log(array $config, array $events) {
    echo(json_encode($events, JSON_PRETTY_PRINT)."\n");
}