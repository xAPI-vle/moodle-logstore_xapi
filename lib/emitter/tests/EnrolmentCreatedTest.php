<?php namespace XREmitter\Tests;
use \XREmitter\Events\EnrolmentCreated as Event;

class EnrolmentCreatedTest extends EventTest {
    protected static $recipe_name = 'enrolment_created';

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
            $this->constructUser('instructor'),
            $this->contructObject('course')
        );
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertVerb('http://www.tincanapi.co.uk/verbs/enrolled_onto_learning_plan', 'enrolled onto', $output['verb']);
        $this->assertObject('course', $input, $output['object']);
        $this->assertUser($input, $output['context']['instructor'], 'instructor');
    }
}
