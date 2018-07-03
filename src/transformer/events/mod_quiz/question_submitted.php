<?php

namespace src\transformer\events\mod_quiz;

use src\transformer\utils as utils;

function question_submitted(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $user = $repo->read_record_by_id('user', $event->userid);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $attempt = $repo->read_record_by_id('quiz_attempts', $event->objectid);
    $quiz = $repo->read_record_by_id('quiz', $attempt->quiz);
    $question_ids = explode(',', $attempt->layout);
    $questions = array();
    foreach ($question_ids as $id) {
        if ($id != '0') {
            $questions[] = $repo->read_record_by_id('question', $id);
        }
    }
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
        'result' => utils\get_question_results($config, $attempt, $questions),
        'context' => [
            'platform' => $config['source_name'],
            'language' => $lang,
            'extensions' => [
                utils\info_extension => utils\get_info($config, $event),
            ],
            'contextActivities' => [
                'other' => [
                    utils\get_activity\module($config, 'attempt', $attempt, $lang),
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