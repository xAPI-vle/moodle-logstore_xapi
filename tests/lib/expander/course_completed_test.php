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

use \LogExpander\Events\Event as Event;

/**
 * Class CourseCompletedTest
 * @package LogExpander\Tests
 */
class course_completed_test extends event_test {
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        parent::setup();
        $this->event = new Event($this->repo);
    }

    /**
     * Construct the event, using even name, course_completed
     * @return array
     */
    protected function construct_input() {
        return array_merge(parent::construct_input(), [
            'objecttable' => null,
            'objectid' => null,
            'eventname' => '\core\event\course_completed',
            'action' => 'completed',
            'target' => 'course',
            'component' => 'core',
        ]);
    }

    /**
     * Assert output is equal to what we expect.
     * @param $input
     * @param $output
     */
    protected function assert_output($input, $output) {
        $this->assert_user($input['userid'], $output['user']);
        $this->assert_user($input['relateduserid'], $output['relateduser']);
        $this->assert_course($input['courseid'], $output['course']);
        $this->assert_site($input, $output['app']);
        $this->assertEquals($input, $output['event']);
        $this->assert_info($input, $output['info']);
    }

    /**
     * Assert Site is what we expect it to be
     * @param $input
     * @param $output
     */
    private function assert_site($input, $output) {
        $this->assertEquals($this->cfg->wwwroot, $output->url);
        $this->assertEquals('site', $output->type);
    }

    /**
     * Assert course is as expected.
     * @param $input
     * @param $output
     */
    private function assert_course($input, $output) {
        $this->assert_record($input, $output);
        $this->assertEquals($this->cfg->wwwroot . '/course/view.php?id=' . $output->id, $output->url);
    }
}
