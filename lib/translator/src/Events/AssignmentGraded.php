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

class AssignmentGraded extends ModuleViewed {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override ModuleViewed
     */
    public function read(array $opts) {
        $scoreraw = (float) ($opts['grade']->grade ?: 0);
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

        // Calculate scaled score as the distance from zero towards the max (or min for negative scores).
        $scorescaled;
        if ($scoreraw >= 0) {
            $scorescaled = $scoreraw / $scoremax;
        } else {
            $scorescaled = $scoreraw / $scoremin;
        }

        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'assignment_graded',
            'graded_user_id' => $opts['graded_user']->id,
            'graded_user_url' => $opts['graded_user']->url,
            'graded_user_name' => $opts['graded_user']->fullname,
            'graded_user_email' => $opts['graded_user']->email,
            'grade_score_raw' => $scoreraw,
            'grade_score_min' => $scoremin,
            'grade_score_max' => $scoremax,
            'grade_score_scaled' => $scorescaled,
            'grade_success' => $success,
            'grade_completed' => true,
            'grade_comment' => strip_tags($opts['grade_comment']),
        ])];
    }
}
