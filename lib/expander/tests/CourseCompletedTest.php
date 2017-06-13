<?php
/**
 * Created by PhpStorm.
 * User: lee.kirkland
 * Date: 5/19/2016
 * Time: 4:25 PM
 */

namespace LogExpander\Tests;
use \LogExpander\Events\Event as Event;

/**
 * Class CourseCompletedTest
 * @package LogExpander\Tests
 */
class CourseCompletedTest extends EventTest
{
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event($this->repo);
    }

    /**
     * Construct the event, using even name, course_completed
     * @return array
     */
    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'objecttable' => null,
            'objectid' => null,
            'eventname' => '\core\event\course_completed',
            'action' =>'completed',
            'target'=>'course',
            'component'=>'core',
        ]);
    }

    /**
     * Assert output is equal to what we expect. 
     * @param $input
     * @param $output
     */
    protected function assertOutput($input, $output) {
        $this->assertUser($input['userid'], $output['user']);
        $this->assertUser($input['relateduserid'], $output['relateduser']);
        $this->assertCourse($input['courseid'], $output['course']);
        $this->assertSite($input, $output['app']);
        $this->assertEquals($input, $output['event']);
        $this->assertInfo($input, $output['info']);
    }

    /**
     * Assert Site is what we expect it to be
     * @param $input 
     * @param $output
     */
    private function assertSite($input, $output) {
        $this->assertEquals($this->cfg->wwwroot, $output->url);
        $this->assertEquals('site', $output->type);
    }

    /**
     * Assert course is as expected.
     * @param $input
     * @param $output
     */
    private function assertCourse($input, $output) {
        $this->assertRecord($input, $output);
        $this->assertEquals($this->cfg->wwwroot . '/course/view.php?id=' . $output->id, $output->url);
    }
}