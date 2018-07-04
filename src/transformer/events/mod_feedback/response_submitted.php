<?php
namespace src\transformer\events\mod_feedback;

defined('MOODLE_INTERNAL') || die();

use src\transformer\utils as utils;

function response_submitted(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $user = $repo->read_record_by_id('user', $event->userid);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $lang = utils\get_course_lang($course);
    $feedbackcompleted = $repo->read_record_by_id('feedback_completed', $event->objectid);
    $feedback = $repo->read_record_by_id('feedback', $feedbackcompleted->feedback);

    return [[
        'actor' => utils\get_user($config, $user),
        'verb' => [
            'id' => 'http://id.tincanapi.com/verb/submitted',
            'display' => [
                $lang => 'submitted'
            ],
        ],
        'object' => utils\get_activity\feedback($config, $feedback, $lang),
        'timestamp' => utils\get_event_timestamp($event),
        'context' => [
            'platform' => $config['source_name'],
            'language' => $lang,
            'extensions' => [
                utils\INFO_EXTENSION => utils\get_info($config, $event),
            ],
            'contextActivities' => [
                'grouping' => [
                    utils\get_activity\site($config),
                    utils\get_activity\course($config, $course),
                ],
                'category' => [
                    utils\get_activity\source($config),
                ]
            ],
        ],
    ]];
}