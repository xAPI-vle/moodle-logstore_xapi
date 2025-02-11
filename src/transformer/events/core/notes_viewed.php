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
 * Transformer fn for notess_viewed event
 *
 * @package   logstore_xapi
 * @copyright Daniel Bell <daniel@yetanalytics.com>
 *
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\events\core;

use src\transformer\utils as utils;
use src\transformer\utils\get_activity as activity;

/**
 * Transformer fn for notess_viewed event
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $event The event to be transformed.
 * @return array
 */

function notes_viewed(array $config, \stdClass $event) {
    $repo = $config['repo'];

    //all three here may not exist
    $user=$repo->read_record_by_id('user', $event->userid);
    $subject=$repo->read_record_by_id('user', $event->relateduserid);
    $course = (isset($event->courseid) && $event->courseid != 0)
        ? $repo->read_record_by_id('course', $event->courseid)
        : null;
    $lang = is_null($course)
        ? $config['source_lang']
    : utils\get_course_lang($course);

    $statement = [
        'actor' => utils\get_user($config,$user),
        'verb' => ['id' => 'http://id.tincanapi.com/verb/viewed',
                   'display' => [
                       'en' => 'Viewed'
                   ]
        ],
        'object' => [
            ...activity\base(),
            'id' => $config['app_url'].'/notes/index.php',
            'definition' => [
                'name' => [
                    $lang => 'Notes'
                ],
                'type' =>  'https://w3id.org/xapi/acrossx/activities/webpage',
                'extensions' => [
                    "https://xapi.edlm/profiles/edlm-lms/concepts/activity-extensions/note-subject"
                        => utils\get_user($config,$subject)
                ]
            ],
        ],
        'context' => [
            ...utils\get_context_base($config, $event, $lang, $course),
            'contextActivities' =>  [
                'category' => [
                    activity\site($config)
                ],
            ],
            'extensions' =>
                array_merge(
                    utils\extensions\base($config, $event, $course),
                    [
                        'https://xapi.edlm/profiles/edlm-lms/concepts/context-extensions/note-subject-scope'
                            => utils\get_user($config, $subject)])

        ]
    ];

    if ($course){
        $statement = utils\add_parent($config,$statement,$course);
    }

    return [$statement];
}
