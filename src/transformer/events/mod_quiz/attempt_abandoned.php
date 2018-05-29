<?php

namespace transformer\events\mod_feedback;

use transformer\utils as utils;

function attempt_abandoned(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $user = $repo->read_record_by_id('user', $event->userid);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $attempt = $repo->read_record_by_id('attempt', $event->objectid);
    $quiz = $repo->read_record_by_id('quiz', $attempt->quiz);
    $lang = utils\get_course_lang($course);

    return [[
        'actor' => utils\get_user($config, $user),
        'verb' => [
            'id' => 'http://adlnet.gov/expapi/verbs/completed',
            'display' => [
                $lang => 'completed'
            ],
        ],
        'object' => utils\get_activity\module($config, 'quiz', $quiz, $lang),
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
            'duration' => ''
        ],
        'context' => [
            'platform' => $config['source_name'],
            'language' => $lang,
            'extensions' => [
                utils\info_extension => utils\get_info($config, $event),
            ],
            'contextActivities' => [
                'other' => [
                    utils\get_activity\module($config, 'attempt', $attempt, $lang)
                ],
                'grouping' => [
                    utils\get_activity\site($config),
                    utils\get_activity\course($config, $course),
                ],
                'category' => [
                    utils\get_activity\source($config),
                ]
            ],
        ]
    ]];
}