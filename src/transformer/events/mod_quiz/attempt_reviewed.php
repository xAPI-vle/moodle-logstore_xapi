<?php

namespace transformer\events\mod_quiz;

use transformer\utils as utils;

function attempt_reviewed(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $learner = $repo->read_record_by_id('user', $event->relateduserid);
    $instructor = $repo->read_record_by_id('user', $event->userid);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $attempt = $repo->read_record_by_id('attempt', $event->objectid);
    $quiz = $repo->read_record_by_id('quiz', $attempt->quiz);
    $lang = utils\get_course_lang($course);

    return [[
        'actor' => utils\get_user($config, $learner),
        'verb' => [
            'id' => 'http://activitystrea.ms/schema/1.0/receive',
            'display' => [
                $lang => 'received'
            ],
        ],
        'object' => [
            'id' => $config['app_url'].'/review.php?attempt='.$attempt->id,
            'definition' => [
                'type' => 'http://activitystrea.ms/schema/1.0/review',
                'name' => [
                    $lang => 'review'
                ]
            ]
        ],
        'timestamp' => utils\get_event_timestamp($event),
        'context' => [
            'instructor' => utils\get_user($config, $instructor),
            'platform' => $config['source_name'],
            'language' => $lang,
            'extensions' => [
                utils\info_extension => utils\get_info($config, $event),
            ],
            'contextActivities' => [
                'grouping' => [
                    utils\get_activity\site($config),
                    utils\get_activity\course($config, $course),
                    utils\get_activity\module($config, 'quiz', $quiz, $lang),
                    utils\get_activity\module($config, 'attempt', $attempt, $lang),
                ],
                'category' => [
                    utils\get_activity\source($config),
                ]
            ],
        ]
    ]];
}