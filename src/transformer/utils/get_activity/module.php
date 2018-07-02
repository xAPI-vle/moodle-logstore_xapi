<?php

namespace src\transformer\utils\get_activity;

use src\transformer\utils as utils;

function module(array $config, $module_type, $module, $lang) {
    return [
        'id' => utils\get_activity_url($config, $module_type, $module->id),
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/lms/module',
            'name' => [
                $lang => property_exists($module_type, 'name') ? $module->name : $module_type,
            ],
        ],
    ];
}
