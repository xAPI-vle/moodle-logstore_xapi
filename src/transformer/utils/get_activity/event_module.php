<?php
namespace src\transformer\utils\get_activity;
defined('MOODLE_INTERNAL') || die();

use src\transformer\utils as utils;

function event_module(array $config, \stdClass $event, $lang) {
    $repo = $config['repo'];
    $moduletype = $event->objecttable;
    $moduleid = $event->objectid;
    $module = $repo->read_record_by_id($moduletype, $moduleid);
    return utils\get_activity\module($config, $moduletype, $module, $lang);
}
