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

namespace src\transformer\events\mod_quiz\attempt_submitted;

defined('MOODLE_INTERNAL') || die();

use src\transformer\utils as utils;

function question_attempt(array $config, \stdClass $event, \stdClass $questionattempt) {
    $repo = $config['repo'];
    $user = $repo->read_record_by_id('user', $event->relateduserid);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $question = $repo->read_record_by_id('question', $questionattempt->questionid);
    $attempt = $repo->read_record_by_id('quiz_attempts', $questionattempt->questionusageid);
    $quiz = $repo->read_record_by_id('quiz', $attempt->quiz);
    $coursemodule = $repo->read_record_by_id('course_modules', $event->contextinstanceid);
    $lang = utils\get_course_lang($course);

    return [[
        'actor' => utils\get_user($config, $user),
        'verb' => [
            'id' => 'http://adlnet.gov/expapi/verbs/answered',
            'display' => [
                $lang => 'answered'
            ],
        ],
        'object' => [
            'id' => $config['app_url'].'/question/question.php?cmid='.$coursemodule->id.'&id='.$question->id,
            'definition' => [
                'type' => 'http://adlnet.gov/expapi/activities/question',
                'name' => [
                    $lang => $questionattempt->questionsummary,
                ]
            ]
        ],
        'timestamp' => utils\get_event_timestamp($event),
        'result' => utils\get_question_attempt_result($question, $questionattempt),
        'context' => [
            'platform' => $config['source_name'],
            'language' => $lang,
            'extensions' => [
                utils\INFO_EXTENSION => utils\get_info($config, $event),
            ],
            'contextActivities' => [
                'grouping' => [
                    utils\get_activity\site($config),
                    utils\get_activity\course($config, $course),
                    utils\get_activity\module($config, 'quiz', $quiz, $lang),
                    utils\get_activity\module($config, 'attempt', $attempt, $lang),
                ],
                'category' => [
                    utils\get_activity\source($config),
                ]
            ],
        ]
    ]];
}