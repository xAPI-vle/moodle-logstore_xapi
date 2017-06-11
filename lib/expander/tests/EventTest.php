<?php namespace LogExpander\Tests;
use \PHPUnit_Framework_TestCase as PhpUnitTestCase;
use \LogExpander\Events\Event as Event;

class EventTest extends PhpUnitTestCase {
    protected $cfg;
    protected $repo;

    public function __construct() {
        $this->cfg = (object) [
            'wwwroot' => 'http://www.example.com',
            'release' => '1.0.0',
        ];
        $this->repo = new TestRepository((object) [], $this->cfg);
    }

    /**
     * Sets up the tests.
     * @override PhpUnitTestCase
     */
    public function setup() {
        $this->event = new Event($this->repo);
    }

    /**
     * Tests the read method of the Event.
     */
    public function testRead() {
        $input = $this->constructInput();
        $output = $this->event->read($input);
        $this->assertOutput($input, $output);
        $this->createExampleFile($output);
    }

    protected function constructInput() {
        return [
            'userid' => 1,
            'relateduserid' => 1,
            'courseid' => 1,
            'timecreated' => 1433946701,
            'eventname' => '\core\event\course_viewed',
        ];
    }

    protected function assertOutput($input, $output) {
        $this->assertUser($input['userid'], $output['user']);
        $this->assertUser($input['relateduserid'], $output['relateduser']);
        $this->assertCourse($input['courseid'], $output['course']);
        $this->assertSite($input, $output['app']);
        $this->assertEquals($input, $output['event']);
        $this->assertInfo($input, $output['info']);
    }

    protected function assertInfo($input, $output) {
        $version = str_replace("\n", "", str_replace("\r", "", file_get_contents(__DIR__.'/../VERSION')));
        $this->assertEquals($this->cfg->release, $output->{'https://moodle.org/'});
        $this->assertEquals($version, $output->{'https://github.com/LearningLocker/Moodle-Log-Expander'});
    }

    protected function assertRecord($input, $output) {
        $this->assertEquals($input, $output->id);
        $this->assertEquals('object', $output->type);
    }

    protected function assertUser($input, $output) {
        $this->assertRecord($input, $output);
        $this->assertEquals($this->cfg->wwwroot, $output->url);
        $this->assertEquals('test_fullname', $output->fullname);
    }

    private function assertCourse($input, $output) {
        $this->assertRecord($input, $output);
        $this->assertEquals($this->cfg->wwwroot . '/course/view.php?id=' . $output->id, $output->url);
    }

    private function assertSite($input, $output) {
        $this->assertEquals($this->cfg->wwwroot, $output->url);
        $this->assertEquals('site', $output->type);
    }

    protected function assertModule($input, $output, $type) {
        $this->assertRecord($input, $output);
        $this->assertEquals($this->cfg->wwwroot . '/mod/'.$type.'/view.php?id=' . $output->id, $output->url);
    }

    protected function assertAttempt($input, $output) {
        $this->assertRecord($input, $output);
        $this->assertEquals($this->cfg->wwwroot . '/mod/quiz/attempt.php?attempt=' . $output->id, $output->url);
    }

    protected function assertDiscussion($input, $output) {
        $this->assertRecord($input, $output);
        $this->assertEquals($this->cfg->wwwroot . '/mod/forum/discuss.php?d=' . $output->id, $output->url);
    }

    protected function createExampleFile($output) {
        $class_array = explode('\\', get_class($this));
        $event_name = str_replace('Test', '', array_pop($class_array));
        $example_file = __DIR__.'/../docs/examples/'.$event_name.'.json';
        file_put_contents($example_file, json_encode($output, JSON_PRETTY_PRINT));
    }
}
