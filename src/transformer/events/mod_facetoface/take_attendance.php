<?php

namespace transformer\events\mod_facetoface;

use transformer\utils as utils;

function take_attendance(array $config, array $event)
{
    $repo = $config['repo'];
    $instructor = $repo->read_user($event['userid']);
    $course = $repo->read_course($event['courseid']);
    $lang = utils\get_course_lang($course);
    $sessionid = $event['objectid'];
    $sessiondates = $repo->read_store_records('facetoface_sessions_dates', ['sessionid' => $sessionid]);
    $signups = $repo->read_store_records('facetoface_signups', ['sessionid' => $sessionid]);
    $sessionduration = 0;
    $statements = [];

    foreach ($signups as $index => $signup) {
        $signups[$index]->statuses = $repo->read_store_records('facetoface_signups_status', ['signupid' => $signup->id]);
        $signups[$index]->attendee = $repo->read_user($signup->userid);
    }

    foreach ($sessiondates as $index => $date) {
        $sessionduration -= $date->timestart;
        $sessionduration += $date->timefinish;
    }

    foreach ($signups as $signupindex => $signup) {
        $signupevent = utils\get_signup_event($signup, $event, $sessionduration);

        if (!is_null($signupevent)) {
            $statement = [
                'actor' => utils\get_user($config, $signupevent['attendee_id']),
                'verb' => [
                    'id' => 'http://adlnet.gov/expapi/verbs/attended',
                    'display' => [
                        $lang => 'attended'
                    ],
                ],
                'object' => utils\get_module_activity($config, $event, $lang),
                'result' => [
                    'duration' => $signupevent['attempt_duration'],
                    'completion' => $signupevent['attempt_completion'],
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
            ];
            array_push($statements, $statement);
        }
    }

    return $statements;
}