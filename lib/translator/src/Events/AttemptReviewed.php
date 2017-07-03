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

namespace MXTranslator\Events;

defined('MOODLE_INTERNAL') || die();

class AttemptReviewed extends AttemptStarted {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override AttemtStarted
     */
    public function read(array $opts) {
        if (isset($opts['attempt']->timefinish)) {
            $seconds = $opts['attempt']->timefinish - $opts['attempt']->timestart;
            $duration = "PT".(string) $seconds."S";
        } else {
            $duration = "PT0S";
        }

        $scoreraw = isset($opts['attempt']->sumgrades) ? $opts['attempt']->sumgrades : 0;
        $scoremin = (float) ($opts['grade_items']->grademin ?: 0);
        $scoremax = (float) ($opts['grade_items']->grademax ?: 0);
        $scorepass = (float) ($opts['grade_items']->gradepass ?: null);
        $success = false;
        // If there is no passing score then success is unknown.
        if ($scorepass == null) {
            $success = null;
        } else if ($scoreraw >= $scorepass) {
            $success = true;
        }

        // It's possible to configure Moodle quizzes such that you can score higher than the maximum grade.
        // This is not allowed by xAPI, so cap the raw at the min/max.
        if ($scoreraw > $scoremax) {
            $scoreraw = $scoremax;
        }
        if ($scoreraw < $scoremin) {
            $scoreraw = $scoremin;
        }

        // Calculate scaled score as the distance from zero towards the max (or min for negative scores).
        if ($scoreraw >= 0) {
            $scorescaled = $scoreraw / $scoremax;
        } else {
            $scorescaled = $scoreraw / $scoremin;
        }

        // Determine if the attempt was marked finished.
        if (isset($opts['attempt']->state)) {
            $completedstate = $opts['attempt']->state === 'finished';
        } else {
            $completedstate = false;
        }

        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'attempt_completed',
            'attempt_score_raw' => $scoreraw,
            'attempt_score_min' => $scoremin,
            'attempt_score_max' => $scoremax,
            'attempt_score_scaled' => $scorescaled,
            'attempt_success' => $success,
            'attempt_completed' => $completedstate,
            'attempt_duration' => $duration,
        ])];
    }

}
