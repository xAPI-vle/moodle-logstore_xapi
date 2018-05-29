<?php

namespace transformer\events\mod_feedback;

use transformer\utils as utils;

function response_submitted(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $user = $repo->read_record_by_id('user', $event->userid);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $lang = utils\get_course_lang($course);

    return [[
        'actor' => utils\get_user($config, $user),
        'verb' => [
            'id' => 'http://adlnet.gov/expapi/verbs/answered',
            'display' => [
                $lang => 'answered'
            ],
        ],
        'object' => utils\get_activity\event_module($config, $event, $lang),
        'timestamp' => utils\get_event_timestamp($event),
        'result' => [
            'score' => [
                'raw' => 0,
                'min' => 0,
                'max' => 0,
                'scaled' => 0
            ],
            'completion' => false,
            'success' => false,
            'response' => ''
        ],
        'context' => [
            'platform' => $config['source_name'],
            'language' => $lang,
            'extensions' => [
                utils\info_extension => utils\get_info($config, $event),
            ],
            'contextActivities' => [
                'grouping' => [
                    utils\get_activity\site($config),
                    utils\get_activity\course($config, $course),
                    utils\get_activity\event_module($config, $event, $lang)
                ],
                'category' => [
                    utils\get_activity\source($config),
                ]
            ],
        ]
    ]];
}