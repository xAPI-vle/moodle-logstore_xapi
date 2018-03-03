<?php

namespace transformer\utils;

function get_module_activity($config, $event, $lang) {
    $module = $config['repo']->read_module($event['objectid'], $event['objecttable']);
    $module_name = $module->name;
    $module_type = xapi_type.$module->type;

    return [
        'id' => $module->url,
        'definition' => [
            'type' => $module_type,
            'name' => [
                $lang => $module_name,
            ],
        ],
    ];
}
