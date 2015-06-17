<?php namespace Tests;
use \MXTranslator\Events\CourseViewed as Event;

class CourseViewedTest extends EventTest {
    protected static $recipe_name = 'course_viewed';
    
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event($this->repo);
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertCourse($input['course'], $output, 'course');
    }
}
