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

class AttemptStarted extends Event {
    protected static $verbdisplay = [
        'en' => 'started'
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
                'id' => 'http://activitystrea.ms/schema/1.0/start',
                'display' => $this->read_verb_display($opts),
            ],
            'object' => [
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
            'context' => [
                'contextActivities' => [
                    'grouping' => [
                        $this->read_course($opts),
                        $this->read_module($opts),
                    ],
                ],
            ],
        ]);
    }
}
