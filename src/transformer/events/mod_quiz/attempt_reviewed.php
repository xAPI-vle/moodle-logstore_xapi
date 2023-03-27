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
 * Transform for the quiz attempt reviewed event.
 *
 * @package   logstore_xapi
 * @copyright Jerret Fowler <jerrett.fowler@gmail.com>
 *            Ryan Smith <https://www.linkedin.com/in/ryan-smith-uk/>
 *            David Pesce <david.pesce@exputo.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\events\mod_quiz;

use Exception;
use src\transformer\utils as utils;

/**
 * Transformer for quiz attempt reviewed event.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $event The event to be transformed.
 * @return array
 */
function attempt_reviewed(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $learner = $repo->read_record_by_id('user', $event->relateduserid);
    $instructor = $repo->read_record_by_id('user', $event->userid);
    try {
        $course = $repo->read_record_by_id('course', $event->courseid);
    } catch (Exception $e) {
        // OBJECT_NOT_FOUND.
        $course = $repo->read_record_by_id('course', 1);
    }
    $cmid = $event->contextinstanceid;
    $attemptid = $event->objectid;
    $lang = utils\get_course_lang($course);

    $object = [
        'id' => $config['app_url'] . '/mod/quiz/review.php?attempt=' . $attemptid,
        'definition' => [
            'type' => 'http://adlnet.gov/expapi/activities/attempt',
            'name' => [
                $lang => 'Attempt'
            ]
        ]
    ];

    // Set JISC specific activity type.
    if (utils\is_enabled_config($config, 'send_jisc_data')) {
        $object = utils\get_activity\course_quiz($config, $course, $cmid);
    }

    return [[
        'actor' => utils\get_user($config, $learner),
        'verb' => utils\get_verb('reviewed', $config, $lang),
        'object' => $object,
        'timestamp' => utils\get_event_timestamp($event),
        'context' => [
            'instructor' => utils\get_user($config, $instructor),
            'platform' => $config['source_name'],
            'language' => $lang,
            'extensions' => utils\extensions\base($config, $event, $course),
            'contextActivities' => [
                'grouping' => [
                    utils\get_activity\site($config),
                    utils\get_activity\course($config, $course),
                    utils\get_activity\course_quiz($config, $course, $cmid),
                    utils\get_activity\quiz_attempt($config, $attemptid, $cmid),
                ],
                'category' => [
                    utils\get_activity\source($config),
                ]
            ],
        ]
    ]];
}
