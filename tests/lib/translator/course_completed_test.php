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

use \MXTranslator\Events\CourseCompleted as Event;

/**
 * Class CourseCompletedTest
 * @package MXTranslator\Tests
 */
class course_completed_test extends event_test {
    /**
     * @var string
     */
    protected static $recipename = 'course_completed';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        parent::setup();
        $this->event = new Event();
    }

    /**
     * Constructs the Input with course completed
     * @return array
     */
    protected function construct_input() {
        return array_merge(parent::construct_input(), [
            'event' => $this->construct_event('\core\event\course_completed'),
        ]);
    }

    /**
     * Constructs the event with the given event name
     * @param $eventname
     * @return array
     */
    private function construct_event($eventname) {
        return [
            'eventname' => $eventname,
            'timecreated' => 1433946701,
        ];
    }

    /**
     * Assets that the input is the same as the output.
     * @param $input
     * @param $output
     */
    protected function assert_output($input, $output) {
        parent::assert_output($input, $output);
        $this->assert_course($input['course'], $output, 'course');
    }
}
