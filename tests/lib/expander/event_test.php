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

require_once(__DIR__ . '/../../../vendor/autoload.php');

use \PHPUnit_Framework_TestCase as PhpUnitTestCase;
use \LogExpander\Events\Event as Event;

class event_test extends \advanced_testcase {
    protected $cfg;
    protected $repo;

    /**
     * Sets up the tests.
     * @override PhpUnitTestCase
     */
    public function setup() {
        $this->cfg = (object) [
            'wwwroot' => 'http://www.example.com',
            'release' => '1.0.0',
        ];
        $this->repo = new TestRepository((object) [], $this->cfg);
        $this->event = new Event($this->repo);
    }

    /**
     * Tests the read method of the Event.
     */
    public function test_read() {
        $input = $this->construct_input();
        $output = $this->event->read($input);
        $this->assert_output($input, $output);
        $this->create_example_file($output);
    }

    protected function construct_input() {
        return [
            'userid' => 1,
            'relateduserid' => 1,
            'courseid' => 1,
            'timecreated' => 1433946701,
            'eventname' => '\core\event\course_viewed',
        ];
    }

    protected function assert_output($input, $output) {
        $this->assert_user($input['userid'], $output['user']);
        $this->assert_user($input['relateduserid'], $output['relateduser']);
        $this->assert_course($input['courseid'], $output['course']);
        $this->assert_site($input, $output['app']);
        $this->assertEquals($input, $output['event']);
        $this->assert_info($input, $output['info']);
    }

    protected function assert_info($input, $output) {
        $this->assertEquals($this->cfg->release, $output->{'https://moodle.org/'});
    }

    protected function assert_record($input, $output) {
        $this->assertEquals($input, $output->id);
        $this->assertEquals('object', $output->type);
    }

    protected function assert_user($input, $output) {
        $this->assert_record($input, $output);
        $this->assertEquals($this->cfg->wwwroot, $output->url);
        $this->assertEquals('test_fullname', $output->fullname);
    }

    private function assert_course($input, $output) {
        $this->assert_record($input, $output);
        $this->assertEquals($this->cfg->wwwroot . '/course/view.php?id=' . $output->id, $output->url);
    }

    private function assert_site($input, $output) {
        $this->assertEquals($this->cfg->wwwroot, $output->url);
        $this->assertEquals('site', $output->type);
    }

    protected function assert_module($input, $output, $type) {
        $this->assert_record($input, $output);
        $this->assertEquals($this->cfg->wwwroot . '/mod/'.$type.'/view.php?id=' . $output->id, $output->url);
    }

    protected function assert_attempt($input, $output) {
        $this->assert_record($input, $output);
        $this->assertEquals($this->cfg->wwwroot . '/mod/quiz/attempt.php?attempt=' . $output->id, $output->url);
    }

    protected function assert_discussion($input, $output) {
        $this->assert_record($input, $output);
        $this->assertEquals($this->cfg->wwwroot . '/mod/forum/discuss.php?d=' . $output->id, $output->url);
    }

    protected function create_example_file($output) {
        $classarray = explode('\\', get_class($this));
        $eventname = str_replace('_test', '', array_pop($classarray));
        $examplefile = __DIR__ . '/../../../lib/expander/docs/examples/' . $eventname . '.json';
        file_put_contents($examplefile, json_encode($output, JSON_PRETTY_PRINT));
    }
}
