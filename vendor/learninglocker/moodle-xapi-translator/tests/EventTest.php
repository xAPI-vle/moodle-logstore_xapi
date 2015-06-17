<?php namespace Tests;
use \PHPUnit_Framework_TestCase as PhpUnitTestCase;
use \MXTranslator\Events\Event as Event;

abstract class EventTest extends PhpUnitTestCase {
    protected static $recipe_name;
    protected $cfg;

    public function __construct() {
        $this->cfg = (object) [
            'wwwroot' => 'http://www.example.com',
        ];
    }

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
    public function testRead() {
        $input = $this->constructInput();
        $output = $this->event->read($input);
        $this->assertOutput($input, $output);
    }

    protected function constructInput() {
        return [
            'user' => $this->constructUser(),
            'course' => $this->constructCourse(),
            'event' => $this->constructEvent('\core\event\course_viewed'),
        ];
    }

    private function constructUser() {
        return (object) [
            'id' => 1,
            'url' => 'http://www.example.com/user_url',
            'username' => 'Test user_name',
        ];
    }

    private function constructEvent($event_name) {
        return [
            'eventname' => $event_name,
            'timecreated' => 1433946701,
        ];
    }

    protected function constructCourse() {
        return (object) [
            'url' => 'http://www.example.com/course_url',
            'fullname' => 'Test course_fullname',
            'summary' => 'Test course_summary',
            'lang' => 'en',
        ];
    }

    protected function assertOutput($input, $output) {
        $this->assertUser($input['user'], $output);
        $this->assertEvent($input['event'], $output);
        $this->assertEquals(static::$recipe_name, $output['recipe']);
    }

    private function assertUser($input, $output) {
        $this->assertEquals($input->id, $output['user_id']);
        $this->assertEquals($input->url, $output['user_url']);
        $this->assertEquals($input->username, $output['user_name']);
    }

    protected function assertCourse($input, $output, $type) {
        $ext_key = 'http://lrs.learninglocker.net/define/extensions/moodle_course';
        $this->assertEquals($input->lang, $output['context_lang']);
        $this->assertEquals($input->url, $output[$type.'_url']);
        $this->assertEquals($input->fullname, $output[$type.'_name']);
        $this->assertEquals($input->summary, $output[$type.'_description']);
        $this->assertEquals($input, $output[$type.'_ext']);
        $this->assertEquals($ext_key, $output[$type.'_ext_key']);
    }

    private function assertEvent($input, $output) {
        $ext_key = 'http://lrs.learninglocker.net/define/extensions/moodle_logstore_standard_log';
        $this->assertEquals('Moodle', $output['context_platform']);
        $this->assertEquals($input, $output['context_ext']);
        $this->assertEquals($ext_key, $output['context_ext_key']);
        $this->assertEquals(date('c', $input['timecreated']), $output['time']);
    }
}
