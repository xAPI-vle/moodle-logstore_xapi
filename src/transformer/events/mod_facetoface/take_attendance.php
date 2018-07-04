<?php
namespace src\transformer\events\mod_facetoface;

defined('MOODLE_INTERNAL') || die();

use src\transformer\utils as utils;

function take_attendance(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $user = $repo->read_record_by_id('user', $event->userid);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $lang = utils\get_course_lang($course);
    $sessionid = $event->objectid;
    $signups = $repo->read_records('facetoface_signups', ['sessionid' => $sessionid]);
    $statements = [];
    $sessionduration = utils\get_session_duration($config, $sessionid);
    
    foreach ($signups as $signup) {
        try {
            $currentstatus = $repo->read_record('facetoface_signups_status', [
                'signupid' => $signup->id,
                'timecreated' => $event->timecreated,
            ]);
            if ($currentstatus->statuscode >= 90) {
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
                        'duration' => "PT".(string) $sessionduration."S",
                        'completion' => $currentstatus->statuscode === 100,
                    ],
                    'context' => [
                        'platform' => $config['source_name'],
                        'language' => $lang,
                        'instructor' => utils\get_user($config, $user),
                        'extensions' => [
                            utils\INFO_EXTENSION => utils\get_info($config, $event),
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
            // @codingStandardsIgnoreLine
            // No current status.
        }
    }

    return $statements;
}