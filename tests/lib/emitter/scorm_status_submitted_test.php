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

namespace XREmitter\Tests;

defined('MOODLE_INTERNAL') || die();

use \XREmitter\Events\ScormStatusSubmitted as Event;

class scorm_status_submitted_test extends scorm_event_test {
    protected static $recipename = 'scorm_status_submitted';

    /**
     * Sets up the tests.
     * @override EventTest
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function assert_output($input, $output) {
        parent::assert_output($input, $output);
        $this->assert_verb('http://adlnet.gov/expapi/verbs/completed', 'completed', $output['verb']);
        $this->assertEquals($input['scorm_status'], $output['verb']['display']['en']);
    }
}
