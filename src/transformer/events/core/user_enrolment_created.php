<?php
namespace src\transformer\events\core;

defined('MOODLE_INTERNAL') || die();

use src\transformer\utils as utils;

function user_enrolment_created(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $user = $repo->read_record_by_id('user', $event->userid);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $lang = utils\get_course_lang($course);

    return[[
        'actor' => utils\get_user($config, $user),
        'verb' => [
            'id' => 'http://adlnet.gov/expapi/verbs/registered',
            'display' => [
                $lang => 'registered'
            ],
        ],
        'object' => utils\get_activity\course($config, $course),
        'timestamp' => utils\get_event_timestamp($event),
        'context' => [
            'platform' => $config['source_name'],
            'language' => $lang,
            'extensions' => [
                utils\INFO_EXTENSION => utils\get_info($config, $event),
            ],
            'contextActivities' => [
                'grouping' => [
                    utils\get_activity\site($config)
                ],
                'category' => [
                    utils\get_activity\source($config)
                ]
            ],
        ]
    ]];

}