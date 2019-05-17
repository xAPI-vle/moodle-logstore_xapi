<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

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
                    'object' => utils\get_activity\course_module(
                        $config,
                        $course,
                        $event->contextinstanceid,
                        'https://w3id.org/xapi/acrossx/activities/face-to-face-discussion'
                    ),
                    'timestamp' => utils\get_event_timestamp($event),
                    'result' => [
                        'duration' => "PT".(string) $sessionduration."S",
                        'completion' => $currentstatus->statuscode === 100,
                    ],
                    'context' => [
                        'platform' => $config['source_name'],
                        'language' => $lang,
                        'instructor' => utils\get_user($config, $user),
                        'extensions' => utils\extensions\base($config, $event, $course),
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
            continue;
        }
    }

    return $statements;
}