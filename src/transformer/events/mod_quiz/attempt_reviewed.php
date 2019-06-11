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

namespace src\transformer\events\mod_quiz;

defined('MOODLE_INTERNAL') || die();

use src\transformer\utils as utils;

function attempt_reviewed(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $learner = $repo->read_record_by_id('user', $event->relateduserid);
    $instructor = $repo->read_record_by_id('user', $event->userid);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $attempt = $repo->read_record_by_id('quiz_attempts', $event->objectid);
    $coursemodule = $repo->read_record_by_id('course_modules', $event->contextinstanceid);
    $quiz = $repo->read_record_by_id('quiz', $attempt->quiz);
    $lang = utils\get_course_lang($course);

    return [[
        'actor' => utils\get_user($config, $learner),
        'verb' => [
            'id' => 'http://activitystrea.ms/schema/1.0/receive',
            'display' => [
                $lang => 'received'
            ],
        ],
        'object' => [
            'id' => $config['app_url'].'/review.php?attempt='.$attempt->id,
            'definition' => [
                'type' => 'http://activitystrea.ms/schema/1.0/review',
                'name' => [
                    $lang => 'review'
                ]
            ]
        ],
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
                    utils\get_activity\course_quiz($config, $course, $event->contextinstanceid),
                    utils\get_activity\quiz_attempt($config, $attempt->id, $coursemodule->id),
                ],
                'category' => [
                    utils\get_activity\source($config),
                ]
            ],
        ]
    ]];
}