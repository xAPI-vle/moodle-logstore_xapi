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

namespace MXTranslator\Tests;

defined('MOODLE_INTERNAL') || die();

use \MXTranslator\Events\ScormEvent as Event;

class scorm_event_test extends module_viewed_test {
    protected static $recipename = 'scorm_event';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        parent::setup();
        $this->event = new Event();
    }

    protected function construct_input() {
        return array_merge(parent::construct_input(), [
            'scorm_scoes_track' => [
                'status' => 'completed',
            ],
            'cmi_data' => [
                'cmivalue' => 'completed',
                'cmielement' => 'cmi.core.lesson_status',
                'attemptid' => 2,
            ],
            'scorm_scoes' => $this->construct_scorm_scoes(),
        ]);
    }

    protected function construct_scorm_scoes() {
        return (object)[
            'id' => 1,
            'scorm' => 1,
            'scormtype' => 'sco',
            'title' => 'Sco title'
        ];
    }
}

