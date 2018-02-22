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

class AttemptCompleted extends Event {
    protected static $verbdisplay = [
        'en' => 'completed'
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
                'id' => 'http://adlnet.gov/expapi/verbs/completed',
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
                'duration' => $opts['attempt_duration'],
            ],
            'object' => [
                'id' => $opts['module_url'],
                'definition' => [
                    'type' => $opts['module_type'],
                    'name' => [
                        $opts['context_lang'] => $opts['module_name'],
                    ],
                    'description' => [
                        $opts['context_lang'] => $opts['module_description'],
                    ],
                ],
            ],
            'context' => [
                'contextActivities' => [
                    'grouping' => [
                        $this->read_course($opts),
                        [
                            'id' => $opts['attempt_url'],
                            'definition' => [
                                'type' => $opts['attempt_type'],
                                'name' => [
                                    $opts['context_lang'] => $opts['attempt_name'],
                                ],
                                'extensions' => [
                                    $opts['attempt_ext_key'] => $opts['attempt_ext']
                                ],
                            ],
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
