<?php namespace XREmitter\Tests;
use \XREmitter\Events\CourseViewed as Event;

class CourseViewedTest extends EventTest {
    protected static $recipeName = 'course_viewed';

    /**
     * Sets up the tests.
     * @override EventTest
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function constructInput() {
        return array_merge(
            parent::constructInput(),
            $this->contructObject('course')
        );
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertVerb('http://id.tincanapi.com/verb/viewed', 'viewed', $output['verb']);
        $this->assertObject('course', $input, $output['object']);
    }
}
