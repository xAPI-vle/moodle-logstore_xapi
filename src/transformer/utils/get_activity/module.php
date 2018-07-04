<?php

namespace src\transformer\utils\get_activity;
defined('MOODLE_INTERNAL') || die();

use src\transformer\utils as utils;

function module(array $config, $moduletype, $module, $lang) {
    return [
        'id' => $config['app_url'].'/mod/'.$moduletype.'/view.php?id='.$module->id,
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/lms/module',
            'name' => [
                $lang => property_exists($module, 'name') ? $module->name : $moduletype,
            ],
        ],
    ];
}
