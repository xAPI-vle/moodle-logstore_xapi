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

function questions_answered(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $user = $repo->read_record_by_id('user', $event->relateduserid);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $attempt = $repo->read_record_by_id('quiz_attempts', $event->objectid);
    $quiz = $repo->read_record_by_id('quiz', $attempt->quiz);
    $gradeitem = $repo->read_record('grade_items', [
        'itemmodule' => 'quiz',
        'iteminstance' => $quiz->id,
    ]);
    $lang = utils\get_course_lang($course);
    $question_attempts = $repo->read_records('question_attempts', ['questionusageid' => $attempt->id]);
    $questions_submitted = array_map(function ($question_attempt)
    use ($config, $event, $user, $course, $attempt, $quiz, $gradeitem, $lang) {
        return ['actor' => utils\get_user($config, $user),
            'verb' => [
                'id' => 'http://adlnet.gov/expapi/verbs/answered',
                'display' => [
                    $lang => 'answered'
                ],
            ],
            'object' => utils\get_activity\quiz($config, 'quiz', $quiz, $lang, $attempt),
            'timestamp' => utils\get_event_timestamp($event),
            'result' => utils\get_question_result($config, $question_attempt),
            'context' => [
                'platform' => $config['source_name'],
                'language' => $lang,
                'extensions' => [
                    utils\INFO_EXTENSION => utils\get_info($config, $event),
                ],
                'contextActivities' => [
                    'other' => [
                        utils\get_activity\module($config, 'attempt', $attempt, $lang),
                    ],
                    'grouping' => [
                        utils\get_activity\site($config),
                        utils\get_activity\course($config, $course),
                    ],
                    'category' => [
                        utils\get_activity\source($config),
                    ]
                ],
            ]
        ];
    }, $question_attempts);
    // We want questions submitted in an indexed array.
    return $questions_submitted;
}