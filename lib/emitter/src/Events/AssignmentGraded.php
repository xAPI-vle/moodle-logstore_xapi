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

namespace XREmitter\Events;

defined('MOODLE_INTERNAL') || die();

class AssignmentGraded extends Event {
    protected static $verbdisplay = [
        'en' => 'received grade for'
    ];

    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        $instructor = parent::read($opts)['actor'];
        $statement = array_merge_recursive(parent::read($opts), [
            'verb' => [
                'id' => 'http://adlnet.gov/expapi/verbs/scored',
                'display' => $this->read_verb_display($opts),
            ],
            'result' => [
                'score' => [
                    'raw' => $opts['grade_score_raw'],
                    'min' => $opts['grade_score_min'],
                    'max' => $opts['grade_score_max'],
                    'scaled' => $opts['grade_score_scaled']
                ],
                'completion' => $opts['grade_completed'],
                'response' => $opts['grade_comment']
            ],
            'object' => $this->read_module($opts),
            'context' => [
                'contextActivities' => [
                    'parent' => [
                        $this->read_course($opts),
                    ],
                ],
                'instructor' => $instructor
            ],
        ]);

        // Excluded from array merge to make sure that the actor is overwritten e.g. if a different IFI is used.
        $statement['actor'] = $this->read_user($opts, "graded_user");

        if (!is_null($opts['grade_success'])) {
            $statement['result']['success'] = $opts['grade_success'];
        }

        return $statement;
    }
}
