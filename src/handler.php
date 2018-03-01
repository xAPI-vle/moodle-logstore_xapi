<?php

namespace src;

function handler($config, $events) {
    $transformer_config = $config['transformer'];
    $loader_config = $config['loader'];

    $transformed_statements = \transformer\handler($transformer_config, $events);
    $loaded_statements = \loader\handler($loader_config, $transformed_statements);

    return $loaded_statements;
}