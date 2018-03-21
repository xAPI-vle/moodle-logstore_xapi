<?php

namespace transformer\utils\get_activity;

function module(array $config, $event, $lang) {
    $module = $config['repo']->read_module($event['objectid'], $event['objecttable']);
    $module_name = $module->name;

    return [
        'id' => $module->id,
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/lms/module',
            'name' => [
                $lang => $module_name,
            ],
        ],
    ];
}
