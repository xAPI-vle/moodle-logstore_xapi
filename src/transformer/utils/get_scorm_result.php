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

function get_scorm_result($scormscoestracks, $rawscore) {
    $maxscore = null;
    $minscore = null;

    foreach ($scormscoestracks as $st) {
        if ($st->element == 'cmi.core.score.min') {
            $minscore = floatval($st->value);
        } else if ($st->element == 'cmi.core.score.max') {
            $maxscore = floatval($st->value);
        }
    }

    if ($maxscore !== null && $minscore !== null) {
        $scaledscore = get_scaled_score($rawscore, $minscore, $maxscore);
        return [
            'score' => [
                'raw' => $rawscore,
                'min' => $minscore,
                'max' => $maxscore,
                'scaled' => $scaledscore,
            ],
        ];
    }

    if ($maxscore !== null && $minscore === null) {
        return [
            'score' => [
                'raw' => $rawscore,
                'max' => $maxscore,
            ],
        ];
    }

    if ($maxscore === null && $minscore !== null) {
        return [
            'score' => [
                'raw' => $rawscore,
                'min' => $minscore,
            ],
        ];
    }

    return [
        'score' => [
            'raw' => $rawscore,
        ],
    ];
}