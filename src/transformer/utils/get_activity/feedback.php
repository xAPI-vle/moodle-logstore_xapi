<?php

namespace src\transformer\utils\get_activity;

use src\transformer\utils as utils;

function feedback(array $config, \stdClass $feedback, $lang) {
    $name = $feedback->name ? $feedback->name : 'A Moodle feedback survey';

    return [
        'id' => $config['app_url'].'/mod/feedback/view.php?id='.$feedback->id,
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/survey',
            'name' => [
                $lang => $name,
            ],
        ],
    ];
}
