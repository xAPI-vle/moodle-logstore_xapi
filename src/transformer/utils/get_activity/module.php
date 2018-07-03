<?php

namespace src\transformer\utils\get_activity;

use src\transformer\utils as utils;

function module(array $config, $module_type, $module, $lang) {
    return [
        'id' => $config['app_url'].'/mod/'.$module_type.'/view.php?id='.$module->id,
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/lms/module',
            'name' => [
                $lang => property_exists($module, 'name') ? $module->name : $module_type,
            ],
        ],
    ];
}
