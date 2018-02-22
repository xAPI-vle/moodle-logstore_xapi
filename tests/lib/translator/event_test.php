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

require_once(__DIR__ . '/../../../vendor/autoload.php');

use \PHPUnit_Framework_TestCase as PhpUnitTestCase;
use \MXTranslator\Events\Event as Event;

abstract class event_test extends \advanced_testcase {
    protected static $xapitype = 'http://id.tincanapi.com/activitytype/lms';
    protected static $recipename;

    /**
     * Sets up the tests.
     * @override PhpUnitTestCase
     */
    public function setup() {
        $this->event = new Event();
    }

    /**
     * Tests the read method of the Event.
     */
    public function test_read() {
        $input = $this->construct_input();
        $outputs = $this->event->read($input);
        $this->assert_outputs($input, $outputs);
        foreach ($outputs as $output) {
            $input = $this->construct_input();
            $this->assert_output($input, $output);
            $this->create_example_file($output);
        }
    }

    protected function construct_input() {
        return [
            'user' => $this->construct_user(),
            'relateduser' => $this->construct_user(),
            'course' => $this->construct_course(),
            'app' => $this->construct_app(),
            'source' => $this->construct_source(),
            'event' => $this->construct_event('\core\event\course_viewed'),
            'info' => $this->construct_info(),
            'sendmbox' => true
        ];
    }

    protected function construct_info() {
        return (object) [
            'https://moodle.org/' => '1.0.0'
        ];
    }

    protected function construct_user() {
        return (object) [
            'id' => 1,
            'url' => 'http://www.example.com/user_url',
            'fullname' => 'Test user_name',
            'email' => 'test@test.com',
        ];
    }

    private function construct_event($eventname) {
        return [
            'eventname' => $eventname,
            'timecreated' => 1433946701,
        ];
    }

    protected function construct_course() {
        return (object) [
            'url' => 'http://www.example.com/course_url',
            'fullname' => 'Test course_fullname',
            'summary' => '<p>Test course_summary</p>',
            'lang' => 'en',
            'type' => 'moodle_course',
        ];
    }

    protected function construct_app() {
        return (object) [
            'url' => 'http://www.example.com',
            'fullname' => 'Test site_fullname',
            'summary' => '<p>Test site_summary</p>',
            'lang' => 'en',
            'type' => 'moodle_site',
        ];
    }

    protected function construct_source() {
        return (object) [
            'url' => 'http://moodle.org',
            'fullname' => 'Moodle',
            'summary' => 'Moodle is a open source learning platform designed to provide educators,'
                .' administrators and learners with a single robust, secure and integrated system'
                .' to create personalised learning environments.',
            'lang' => 'en',
            'type' => 'moodle_source',
        ];
    }

    protected function assert_outputs($input, $output) {
        // Output is an associative array.
        $this->assertEquals(0, count(array_filter(array_keys($output), 'is_string')));
        // Length of output is 1. Overwrite this function if a different value is needed.
        $this->assertEquals(1 , count($output));
    }

    protected function assert_output($input, $output) {
        $this->assert_app($input['app'], $output, 'app');
        $this->assert_event($input['event'], $output);
        $this->assertEquals(static::$recipename, $output['recipe']);
        $this->assert_info($input['info'], $output['context_info']);
    }

    protected function assert_user($input, $output, $type) {
        $this->assertEquals($input->id, $output[$type.'_id']);
        $this->assertEquals($input->url, $output[$type.'_url']);
        $this->assertEquals($input->fullname, $output[$type.'_name']);
    }

    protected function assert_course($input, $output, $type) {
        $extkey = 'http://lrs.learninglocker.net/define/extensions/moodle_course';
        $this->assertEquals($input->lang, $output['context_lang']);
        $this->assertEquals($input->url, $output[$type.'_url']);
        $this->assertEquals($input->fullname, $output[$type.'_name']);
        $this->assertEquals(strip_tags($input->summary), $output[$type.'_description']);
        $this->assertEquals(static::$xapitype.$input->type, $output[$type.'_type']);
        $this->assertEquals($input, $output[$type.'_ext']);
        $this->assertEquals($extkey, $output[$type.'_ext_key']);
    }

    protected function assert_app($input, $output, $type) {
        $extkey = 'http://lrs.learninglocker.net/define/extensions/moodle_course';
        $apptype = 'http://id.tincanapi.com/activitytype/site';
        $this->assertEquals($input->lang, $output['context_lang']);
        $this->assertEquals($input->url, $output[$type.'_url']);
        $this->assertEquals($input->fullname, $output[$type.'_name']);
        $this->assertEquals(strip_tags($input->summary), $output[$type.'_description']);
        $this->assertEquals($apptype, $output[$type.'_type']);
        $this->assertEquals($input, $output[$type.'_ext']);
        $this->assertEquals($extkey, $output[$type.'_ext_key']);
    }

    protected function assert_source($input, $output, $type) {
        $apptype = 'http://id.tincanapi.com/activitytype/source';
        $this->assertEquals($input->lang, $output['context_lang']);
        $this->assertEquals($input->url, $output[$type.'_url']);
        $this->assertEquals($input->fullname, $output[$type.'_name']);
        $this->assertEquals(strip_tags($input->summary), $output[$type.'_description']);
        $this->assertEquals($apptype, $output[$type.'_type']);
    }

    private function assert_event($input, $output) {
        $extkey = 'http://lrs.learninglocker.net/define/extensions/moodle_logstore_standard_log';
        $this->assertEquals('Moodle', $output['context_platform']);
        $this->assertEquals($input, $output['context_ext']);
        $this->assertEquals($extkey, $output['context_ext_key']);
        $this->assertEquals(date('c', $input['timecreated']), $output['time']);
    }

    private function assert_info($input, $output) {
        $this->assertEquals(
            $input->{'https://moodle.org/'},
            $output->{'https://moodle.org/'}
        );
    }

    protected function create_example_file($output) {
        $classarray = explode('\\', get_class($this));
        $eventname = str_replace('_test', '', array_pop($classarray));
        $examplefile = __DIR__ . '/../../../lib/translator/docs/examples/' . $eventname . '.json';
        file_put_contents($examplefile, json_encode($output, JSON_PRETTY_PRINT));
    }
}
