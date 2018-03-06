<?php

namespace transformer\events\mod_assign;

use transformer\utils as utils;

function assignment_submitted(array $config, array $event) {
    $repo = $config['repo'];
    $user = $repo->read_user($event['userid']);
    $course = $repo->read_course($event['courseid']);
    $lang = utils\get_course_lang($course);

    return [[
        'actor' => utils\get_user($config, $user),
        'verb' => [
            'id' => 'http://activitystrea.ms/schema/1.0/submit',
            'display' => [
                $lang => 'submitted'
            ],
        ],
        'object' => utils\get_module_activity($config, $event, $lang),
        'timestamp' => utils\get_event_timestamp($event),
        'context' => [
            'platform' => $config['source_name'],
            'language' => $lang,
            'extensions' => [
                utils\info_extension => utils\get_info($config, $event),
            ],
            'contextActivities' => [
                'grouping' => [
                    utils\get_course_activity($course)
                ],
                'category' => [
                    utils\get_source_activity($config)
                ]
            ],
        ]
    ]];
}
