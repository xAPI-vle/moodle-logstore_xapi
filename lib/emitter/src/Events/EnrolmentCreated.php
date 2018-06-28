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

class EnrolmentCreated extends Event {
    protected static $verbdisplay = [
        'en' => 'enrolled onto'
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
                'id' => 'http://www.tincanapi.co.uk/verbs/enrolled_onto_learning_plan',
                'display' => $this->read_verb_display($opts),
            ],
            'object' => $this->read_course($opts),
            'context' => [
                'instructor' => $this->read_user($opts, 'instructor'),
            ],
        ]);
    }
}
