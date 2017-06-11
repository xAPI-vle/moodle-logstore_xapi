<?php namespace XREmitter\Tests;
use \XREmitter\Events\ScormStatusSubmitted as Event;

class ScormStatusSubmittedTest extends ScormEventTest {
    protected static $recipe_name = 'scorm_status_submitted';

    /**
     * Sets up the tests.
     * @override EventTest
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertVerb('http://adlnet.gov/expapi/verbs/completed', 'completed', $output['verb']);
        $this->assertEquals($input['scorm_status'], $output['verb']['display']['en']);
    }
}
