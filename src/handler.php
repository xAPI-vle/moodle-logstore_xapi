<?php

namespace src;

defined('MOODLE_INTERNAL') || die();

function handler($config, $events) {
    $logerror = $config['log_error'];
    $loginfo = $config['log_info'];
    try {
        $transformerconfig = array_merge([
            'log_error' => $logerror,
            'log_info' => $loginfo,
        ], $config['transformer']);

        $loaderconfig = array_merge([
            'log_error' => $logerror,
            'log_info' => $loginfo,
        ], $config['loader']);

        $statements = \src\transformer\handler($transformerconfig, $events);
        \src\loader\handler($loaderconfig, $statements);

        return $statements;
    } catch (\Exception $e) {
        $logerror($e);
        return [];
    }
}