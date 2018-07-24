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

namespace src\transformer\utils;
defined('MOODLE_INTERNAL') || die();

function get_question_result(array $config, $question_attempt) {
    $repo = $config['repo'];
    $result = $repo->read_records('question_attempt_steps', ['questionattemptid' => $question_attempt->id]);
    $answer_result = 'gaveup';
    $fraction = 0.000;
    // Since the sequence numbers are always 0-3 the last element should be either 2 or 3 depending if they completed
    // the question or gave up.
    if (array_key_exists('1', $result) && $result['1']->state == 'completed') {
        $answer_result = $result['2']->state;
        $fraction = $result['2']->fraction;
    }

    $completed = isset($question_attempt->state) ? $answer_result !== 'gaveup' : false;
    $success = $answer_result === 'gradedright';

    return [
        'response' => $question_attempt->responsesummary,
        'score' => [
            'raw' => $fraction,
            'min' => $question_attempt->minfraction,
            'max' => $question_attempt->maxmark,
            'scaled' => $fraction,
        ],
        'completion' => $completed,
        'success' => $success,
    ];
}
