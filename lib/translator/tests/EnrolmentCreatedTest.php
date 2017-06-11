<?php namespace MXTranslator\Tests;
use \MXTranslator\Events\EnrolmentCreated as Event;

class EnrolmentCreatedTest extends EventTest {
    protected static $recipe_name = 'enrolment_created';
    
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertUser($input['user'], $output, 'instructor');
        $this->assertUser($input['relateduser'], $output, 'user');
        $this->assertCourse($input['course'], $output, 'course');
    }
}
