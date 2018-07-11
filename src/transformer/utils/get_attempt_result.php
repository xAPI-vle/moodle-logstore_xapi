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
    $gradesum = isset($attempt->sumgrades) ? $attempt->sumgrades : 0;

    $minscore = (float)($grade_item->grademin ? $grade_item->grademin : 0);
    $maxscore = (float)($grade_item->grademax ? $grade_item->grademax : 0);
    $passscore = (float)($grade_item->gradepass ? $grade_item->gradepass : 0);

    $rawscore = floatval((cap_raw_score($gradesum, $minscore, $maxscore)));
    $scaledscore = floatval((get_scaled_score($rawscore, $minscore, $maxscore)));

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
        'completion' => $completed,
        'success' => $success,
        'duration' => $duration,
    ];
}
