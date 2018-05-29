<?php

namespace transformer\events\mod_facetoface;

use transformer\utils as utils;

function take_attendance(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $user = $repo->read_record_by_id('user', $event->userid);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $lang = utils\get_course_lang($course);
    $session_id = $event->objectid;
    $signups = $repo->read_records('facetoface_signups', ['sessionid' => $session_id]);
    $statements = [];
    $session_duration = utils\get_session_duration($config, $session_id);
    
    foreach ($signups as $signup) {
        try {
            $current_status = $repo->read_record('facetoface_signups_status', [
                'signupid' => $signup->id,
                'timecreated' => $event->timecreated,
            ]);
            if ($current_status->statuscode >= 90) {
                $attendee = $repo->read_record_by_id('user', $signup->userid);
                $statement = [
                    'actor' => utils\get_user($config, $attendee),
                    'verb' => [
                        'id' => 'http://adlnet.gov/expapi/verbs/attended',
                        'display' => [
                            $lang => 'attended'
                        ],
                    ],
                    'object' => utils\get_activity\event_module($config, $event, $lang),
                    'timestamp' => utils\get_event_timestamp($event),
                    'result' => [
                        'duration' => "PT".(string) $session_duration."S",
                        'completion' => $current_status->statuscode === 100,
                    ],
                    'context' => [
                        'platform' => $config['source_name'],
                        'language' => $lang,
                        'instructor' => utils\get_user($config, $user),
                        'extensions' => [
                            utils\info_extension => utils\get_info($config, $event),
                        ],
                        'contextActivities' => [
                            'grouping' => [
                                utils\get_activity\site($config),
                                utils\get_activity\course($config, $course),
                            ],
                            'category' => [
                                utils\get_activity\source($config)
                            ]
                        ],
                    ],
                ];
                array_push($statements, $statement);
            }
        } catch (\Exception $ex) {
            // No current status.
        }
    }

    return $statements;
}