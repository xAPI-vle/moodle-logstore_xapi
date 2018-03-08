<?php

namespace transformer\events\mod_facetoface;

use transformer\utils as utils;

function take_attendance(array $config, array $event) {
    $repo = $config['repo'];
    $user = $repo->read_user($event['userid']);
    $instructor = $repo->read_user($event['userid']);
    $site = $repo->read_site();
    $course = $repo->read_course($event['courseid']);
    $lang = utils\get_course_lang($course);

    $sessionid = $event['objectid'];
    $sessionname = 'Session '.$sessionid.' of '.$event['module']->name;
    $session = $repo->read_object($sessionid, 'facetoface_sessions');
    $sessiondates = $repo->read_store_records('facetoface_sessions_dates', ['sessionid' => $sessionid]);
    $sessionurl = $site->url . '/mod/facetoface/signup.php?s=' . $sessionid;

    //TODO I believe I need to loop through all the attendees and generate a statement for each. Then return the array of statements

    return [[
        'actor' => utils\get_user($config, $user),
        'verb' => [
            'id' => 'http://adlnet.gov/expapi/verbs/attended',
            'display' => [
                $lang => 'attended'
            ],
        ],
        'object' => utils\get_module_activity($config, $event, $lang),
        'result' => [
            'duration' => $event['attempt_duration'],
            'completion' => $event['attempt_completion'],
        ],
        'timestamp' => utils\get_event_timestamp($event),
        'context' => [
            'platform' => $config['source_name'],
            'language' => $lang,
            'instructor' => $instructor,
            'extensions' => [
                utils\info_extension => utils\get_info($config, $event),
            ],
            'contextActivities' => [
                'grouping' => [
                    utils\get_course_activity($course),
                ],
                'category' => [
                    utils\get_source_activity($config),
                ]
            ],
        ]
    ]];
}