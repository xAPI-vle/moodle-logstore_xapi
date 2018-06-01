<?php

namespace src\transformer\utils\get_activity;

use src\transformer\utils as utils;

function event_module(array $config, \stdClass $event, $lang) {
    $repo = $config['repo'];
    $module_type = $event->objecttable;
    $module_id = $event->objectid;
    $module = $repo->read_record_by_id($module_type, $module_id);
    return utils\get_activity\module($config, $module_type, $module, $lang);
}
