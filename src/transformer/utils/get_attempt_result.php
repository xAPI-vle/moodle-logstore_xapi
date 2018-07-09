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

use src\transformer\utils as utils;

function get_attempt_result(array $config, $attempt, $grade_item) {
    $grade_sum = isset($attempt->sumgrades) ? $attempt->sumgrades : 0;

    $min_score = (float)($grade_item->grademin ? $grade_item->grademin : 0);
    $max_score = (float)($grade_item->grademax ? $grade_item->grademax : 0);
    $pass_score = (float)($grade_item->gradepass ? $grade_item->gradepass : null);

    $raw_score = floatval((cap_raw_score($grade_sum, $min_score, $max_score)));
    $scaled_score = floatval((get_scaled_score($raw_score, $min_score, $max_score)));

    $completed = isset($attempt->state) ? $attempt->state === 'finished' : false;
    $success = $gradesum >= $passscore;
    $duration = get_attempt_duration($attempt);

    return [
        'score' => [
            'raw' => $rawscore,
            'min' => $minscore,
            'max' => $maxscore,
            'scaled' => $scaledscore,
        ],
        'responses' => utils\get_attempt_responses($config, $attempt, $grade_item),
        'completion' => $completed,
        'success' => $success,
        'duration' => $duration,
    ];
}
