<?php

namespace src;

function handler($config, $events) {
    $log_error = $config['log_error'];
    $log_info = $config['log_info'];
    try {
        $transformer_config = array_merge([
            'log_error' => $log_error,
            'log_info' => $log_info,
        ], $config['transformer']);

        $loader_config = array_merge([
            'log_error' => $log_error,
            'log_info' => $log_info,
        ], $config['loader']);
        $log_info('yo');

        $statements = \src\transformer\handler($transformer_config, $events);
        \src\loader\handler($loader_config, $statements);

        return $statements;
    } catch (Exception $e) {
        $log_error($e);
        return [];
    }
}