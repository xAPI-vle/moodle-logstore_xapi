<?php namespace XREmitter\Tests;
use \XREmitter\Events\AttemptStarted as Event;

class AttemptStartedTest extends EventTest {
    protected static $recipe_name = 'attempt_started';

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
            $this->contructObject('course'),
            $this->contructObject('module'),
            $this->constructAttempt()
        );
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertVerb('http://activitystrea.ms/schema/1.0/start', 'started', $output['verb']);
        $this->assertObject('module', $input, $output['context']['contextActivities']['grouping'][2]);
        $this->assertObject('course', $input, $output['context']['contextActivities']['grouping'][1]);
        $this->assertAttempt($input, $output['object']);
    }
}
