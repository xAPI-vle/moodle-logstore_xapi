<?php namespace MXTranslator\Tests;
use \MXTranslator\Events\CourseViewed as Event;

class CourseViewedTest extends UserEventTest {
    protected static $recipeName = 'course_viewed';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertCourse($input['course'], $output, 'course');
    }
}
