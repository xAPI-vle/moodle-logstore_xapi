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

/**
 * Transform for course viewed event.
 *
 * @package   logstore_xapi
 * @copyright Jerret Fowler <jerrett.fowler@gmail.com>
 *            Ryan Smith <https://www.linkedin.com/in/ryan-smith-uk/>
 *            David Pesce <david.pesce@exputo.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\events\core;

use src\transformer\utils as utils;

/**
 * Transforms badge_awarded event to an "Achieved" xapi event
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $event The event to be transformed.
 * @return array
 */

function badge_awarded(array $config, \stdClass $event) {
    global $CFG;
    $repo = $config['repo'];
    if (isset($event->objecttable) && isset($event->objectid)) {
        $event_object = $repo->read_record_by_id($event->objecttable, $event->objectid);
    } else {
        $event_object = array();
    }

    $recipient = $repo->read_record_by_id('user', $event->relateduserid);
    $actor = utils\get_user($config, $recipient);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $lang = utils\get_course_lang($course);
    $badge = $repo->read_record_by_id('badge', $event->objectid);
    $other = unserialize($event->other);
    $issuedid = $other['badgeissuedid'];
    $manual = $repo->read_record_by_id('badge_manual_award', $issuedid);
    $awarder = $manual ? (utils\get_user($config, $repo->read_record_by_id('user', $manual->issuerid))) : 'System';
    $badgetype = [1 => "Global", 2 => "Course"][$badge->type];
    $course = $badge->courseid ? $repo->read_record_by_id('course', $badge->courseid) : null;
    
    $statement = [[
        'actor' => $actor,
        'verb' => [
            'id' => 'https://w3id.org/xapi/tla/verbs/achieved',
                   'display' => [
                       'en' => 'Achieved'
                   ]],
        'object' => [
            'id' =>  $config['app_url'].'/badges/overview.php?id='.$event->objectid,
            'objectType' => 'Activity',
            'definition' => [
                'name' => [$lang => $badge->name],
                'description' => [$lang => $badge->description],
                'type' => 'https://xapi.edlm/profiles/edlm-lms/concepts/activity-types/badge',
                'extensions' => [
                    'https://xapi.edlm/profiles/edlm-lms/v1/concepts/activity-extensions/badge-type' =>  $badgetype,
                    'https://xapi.edlm/profiles/edlm-lms/v1/concepts/activity-extensions/badge-version' => $badge->version                             
                ]
            ],
        ],
        'result' => [
            'response' => $badge->message
        ],
        'context' => [
            'language' => $lang,
            'instructor' => $awarder,
            'contextActivities' =>  [
                'category' => [
                    'id' => $config['app_url'],
                    'objectType' => 'Activity',
                    'definition' => [
                        'name' => [
                            'en' => 'EDLM Moodle LMS'
                        ],
                        'type' => 'http://id.tincanapi.com/activitytype/lms'
                    ]
                ],
            ],
            'extensions' => array_merge(utils\extensions\base($config, $event, $course),[
                'https://xapi.edlm/profiles/edlm-lms/v1/concepts/context-extensions/badge-assignment-method' => ($manual ? 'Manual' : 'Automatic')])
        ]]];
    if ($course){
        $statement[0]['context']['contextActivities']['parent'] = [
            'id' => $config['app_url'].'/course/view.php?id='.$course->id,
            'objectType' => 'Activity',
            'definition' => [
                'name' => [$lang => $course->fullname],
                'description' => [$lang => $course->summary],
                'type' => 'https://w3id.org/xapi/cmi5/activitytype/course'
            ]
        ];
    }
    
    return $statement;
}
