<?php

namespace src;

function handler($config, $events) {
    $transformer_config = $config['transformer'];
    $loader_config = $config['loader'];

    $statements = \src\transformer\handler($transformer_config, $events);
    \src\loader\handler($loader_config, $statements);

    return $statements;
}