<?php

namespace src\transformer\events\mod_quiz;

use src\transformer\utils as utils;

function attempt_submitted(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $user = $repo->read_record_by_id('user', $event->userid);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $attempt = $repo->read_record_by_id('quiz_attempts', $event->objectid);
    $quiz = $repo->read_record_by_id('quiz', $attempt->quiz);
    $grade_item = $repo->read_record('grade_items', [
        'itemmodule' => 'quiz',
        'iteminstance' => $quiz->id,
    ]);
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
        'result' => utils\get_attempt_result($config, $attempt, $grade_item),
        'context' => [
            'platform' => $config['source_name'],
            'language' => $lang,
            'extensions' => [
                utils\info_extension => utils\get_info($config, $event),
            ],
            'contextActivities' => [
                'other' => [
                    utils\get_activity\module($config, 'attempt', $attempt, $lang),
                   // 'response' => utils\get_attempt_responses($config, $attempt, $grade_item),
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