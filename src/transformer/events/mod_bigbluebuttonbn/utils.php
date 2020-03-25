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

namespace src\transformer\events\mod_bigbluebuttonbn;

defined('MOODLE_INTERNAL') || die();

use src\transformer\utils as utils;

/**
 * Create a Statement for the logs.
 *
 * @author Paul Walter (https://github.com/paulito-bandito)
 * @param array $config
 * @param \stdClass $event
 * @param $evtid                The URL of the Verb we wish to use.
 *                                  (example: 'http://activitystrea.ms/schema/1.0/leave' )
 * @param $evtdispname         The conjugated Verb so it reads better in SCORM log.
 *                                  (example: 'left' )
 * @return array
 */
function create_stmt(array $config, \stdClass $event, $evtid, $evtdispname ) {
    $repo = $config['repo'];
    $user = $repo->read_record_by_id('user', $event->userid);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $lang = utils\get_course_lang($course);

    return [[
        'actor' => utils\get_user($config, $user),
        'verb' => [
            'id' => $evtid,
            'display' => [
                $lang => $evtdispname
            ],
        ],
        'object' => utils\get_activity\course_module(
            $config,
            $course,
            $event->contextinstanceid,
            'http://adlnet.gov/expapi/activities/link'
        ),
        'timestamp' => utils\get_event_timestamp($event),
        'context' => [
            'platform' => $config['source_name'],
            'language' => $lang,
            'extensions' => utils\extensions\base($config, $event, $course),
            'contextActivities' => [
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