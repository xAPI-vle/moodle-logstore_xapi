<?php

namespace transformer\events\mod_assign;

use transformer\utils as utils;

function assignment_graded(array $config, array $event) {
    $repo = $config['repo'];
    $user = $repo->read_user($event['relateduserid']);
    $course = $repo->read_course($event['courseid']);
    $instructor = $repo->read_user($event['userid']);
    $lang = utils\get_course_lang($course);

    return [[
        'actor' => utils\get_user($config, $user),
        'verb' => [
            'id' => 'http://adlnet.gov/expapi/verbs/scored',
            'display' => [
                $lang => 'scored'
            ],
        ],
        'object' => utils\get_module_activity($config, $event, $lang),
        'result' => [
            'score' => [
                'raw' => $event['grade_score_raw'],
                'min' => $event['grade_score_min'],
                'max' => $event['grade_score_max'],
                'scaled' => $event['grade_score_scaled']
            ],
            'completion' => $event['grade_completed'],
            'response' => $event['grade_comment']
        ],
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
            'instructor' => $instructor
        ]
    ]];
}
