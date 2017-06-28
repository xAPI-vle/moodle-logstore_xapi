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

namespace LogExpander\Tests;

defined('MOODLE_INTERNAL') || die();

use \LogExpander\Events\ScormSubmitted as Event;

class scorm_submitted_test extends event_test {
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        parent::setup();
        $this->event = new Event($this->repo);
    }

    protected function construct_input() {
        return array_merge(parent::construct_input(), [
            'objecttable' => 'scorm_scoes_track',
            'objectid' => 1,
            'contextinstanceid' => 1,
            'eventname' => '\mod_scorm\event\scoreraw_submitted',
            'other' => 'a:3:{s:9:"attemptid";i:1;s:10:"cmielement";s:18:"cmi.core.score.raw";s:8:"cmivalue";s:3:"100";}',
        ]);
    }

    protected function assert_output($input, $output) {
        parent::assert_output($input, $output);
        $this->assert_module(1, $output['module'], 'scorm');
        $this->assertEquals(1, $output['scorm_scoes']->id);
        $this->assert_scorm_scoes_track($output);
        $this->assert_cmi_data($output);
    }

    protected function assert_scorm_scoes_track($output) {
        $this->assertEquals('status', $output['scorm_scoes_track']['status']);
        $this->assertEquals(100, $output['scorm_scoes_track']['scoremax']);
        $this->assertEquals(0, $output['scorm_scoes_track']['scoremin']);
    }

    protected function assert_cmi_data($output) {
        $this->assertEquals(1, $output['cmi_data']['attemptid']);
        $this->assertEquals('cmi.core.score.raw', $output['cmi_data']['cmielement']);
        $this->assertEquals(100, $output['cmi_data']['cmivalue']);
    }
}
