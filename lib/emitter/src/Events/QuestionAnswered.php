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

class QuestionAnswered extends Event {
    protected static $verbdisplay = [
        'en' => 'answered'
    ];

    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {

        $statement = [
            'verb' => [
                'id' => 'http://adlnet.gov/expapi/verbs/answered',
                'display' => $this->read_verb_display($opts),
            ],
            'result' => [
                'score' => [
                    'raw' => $opts['attempt_score_raw'],
                    'min' => $opts['attempt_score_min'],
                    'max' => $opts['attempt_score_max'],
                    'scaled' => $opts['attempt_score_scaled']
                ],
                'completion' => $opts['attempt_completed'],
                'response' => $opts['attempt_response']
            ],
            'object' => $this->read_question($opts),
            'context' => [
                'contextActivities' => [
                    'parent' => [
                        $this->read_module($opts)
                    ],
                    'grouping' => [
                        $this->read_course($opts),
                        [
                            'id' => $opts['attempt_url']
                        ],
                    ],
                ],
            ],
        ];

        if (!is_null($opts['attempt_success'])) {
            $statement['result']['success'] = $opts['attempt_success'];
        }

        return array_merge_recursive(parent::read($opts), $statement);
    }
}
