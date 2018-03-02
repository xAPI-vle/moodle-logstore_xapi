<?php

namespace src;

function handler($config, $events) {
    $transformer_config = $config['transformer'];
    $loader_config = $config['loader'];

    $statements = \transformer\handler($transformer_config, $events);
    \loader\handler($loader_config, $statements);

    return $statements;
}