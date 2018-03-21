<?php

namespace transformer\utils\get_activity;

use transformer\utils as utils;

function module(array $config, \stdClass $event, $lang) {
    $module = $config['repo']->read_record_by_id($event->objecttable, $event->objectid);
    $module_name = $module->name;

    return [
        'id' => utils\get_activity_url($config, $event->objecttable, $event->objectid),
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/lms/module',
            'name' => [
                $lang => $module_name,
            ],
        ],
    ];
}
