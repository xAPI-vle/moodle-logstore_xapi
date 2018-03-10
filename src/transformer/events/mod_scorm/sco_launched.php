<?php

namespace transformer\events\mod_scorm;

use transformer\utils as utils;

function sco_launched(array $config, array $event) {
    $repo = $config['repo'];
    $user = $repo->read_user($event['userid']);
    $course = $repo->read_course($event['courseid']);
    $lang = utils\get_course_lang($course);

    return [[
        'actor' => utils\get_user($config, $user),
        'verb' => [
            'id' => 'http://adlnet.gov/expapi/verbs/launched',
            'display' => [
                $lang => 'launched'
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
