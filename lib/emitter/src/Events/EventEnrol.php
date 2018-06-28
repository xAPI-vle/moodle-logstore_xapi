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

class EventEnrol extends Event {
    protected static $verbdisplay = [
        'en' => 'registered for'
    ];

    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        return array_merge_recursive(parent::read($opts), [
            'verb' => [
                'id' => 'http://adlnet.gov/expapi/verbs/registered',
                'display' => $this->read_verb_display($opts),
            ],
            'object' => [
                'id' => $opts['session_url'],
                'definition' => [
                    'type' => $opts['session_type'],
                    'name' => [
                        $opts['context_lang'] => $opts['session_name'],
                    ],
                    'description' => [
                        $opts['context_lang'] => $opts['session_description'],
                    ]
                ],
            ],
            'context' => [
                'contextActivities' => [
                    'grouping' => [
                        $this->read_course($opts),
                    ],
                    'parent' => [
                        $this->read_module($opts),
                    ],
                    'category' => [
                            [
                                'id' => 'http://xapi.trainingevidencesystems.com/recipes/attendance/0_0_1#detailed',
                                'definition' => [
                                    'type' => 'http://id.tincanapi.com/activitytype/recipe'
                                ]
                            ]
                        ],
                    ],
                ],
            ]
        );
    }
}
