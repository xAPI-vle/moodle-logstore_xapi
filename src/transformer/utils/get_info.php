<?php
namespace src\transformer\utils;
defined('MOODLE_INTERNAL') || die();

function get_info(array $config, \stdClass $event) {
    return [
        $config['source_url'] => $config['source_version'],
        $config['plugin_url'] => $config['plugin_version'],
        'event_name' => $event->eventname,
        'event_function' => $config['event_function'],
    ];
}
