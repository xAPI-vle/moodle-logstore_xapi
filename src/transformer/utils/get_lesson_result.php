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
 * Transformer utility for retrieving the result/success state from a module completion object.
 *
 * @package   logstore_xapi
 * @copyright Cliff Casey <cliff@yetanalytics.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils;

/**
 * Transformer utility for retrieving the result/success state from a module completion object.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $lesson The lesson object.
 * @param int $userid User ID who completed the lesson
 * @param int $cmid Course Module ID.
 * @return object
 */
function get_lesson_result(array $config, \stdClass $lesson, int $userid, int $cmid) {
    
    $repo = $config['repo'];
    $result = [
        'completion' => true
    ];

    // Score and success if exists
    $gradeitem = $repo->read_record('grade_items', [
        'itemmodule' => 'lesson',
        'iteminstance' => $lesson->id
    ]);

    if (!empty($gradeitem)) {
        $grades = $repo->read_records('grade_grades', [
            'itemid' => $gradeitem->id,
            'userid' => $userid
        ], 'timemodified DESC');
    
        if (!empty($grades)) {
            $grade = reset($grades);
            $min = floatval($grade->rawgrademin ?: 0);
            $max = floatval($grade->rawgrademax ?: 0);
            $raw = floatval($grade->rawgrade ?: 0);
            $pass = floatval($gradeitem->gradepass ?: 0);

            // For xAPI validity safety, because it is somehow possible to 
            // configure a scale with raw score less than the min, this check 
            // must happen or scaled score may be less than -1
            $raw = ($raw < $min) ? $min : $raw;

            $result['score'] = [
                'min' => $min,
                'max' => $max,
                'raw' => $raw,
                'scaled' => get_scaled_score($raw, $min, $max)
            ];

            $result['success'] = ($raw >= $pass);
        }
    }

    return $result;
}
