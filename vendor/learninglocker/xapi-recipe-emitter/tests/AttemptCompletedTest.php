<?php namespace Tests;
use \XREmitter\Events\AttemptCompleted as Event;

class AttemptCompletedTest extends EventTest {
    protected static $recipe_name = 'attempt_completed';

    /**
     * Sets up the tests.
     * @override EventTest
     */
    public function setup() {
        $this->event = new Event($this->repo);
    }

    protected function constructInput() {
        return array_merge(
            parent::constructInput(),
            $this->contructObject('course'),
            $this->contructObject('module'),
            $this->constructAttempt()
        );
    }

    protected function constructAttempt() {
        return array_merge(parent::constructAttempt(), [
            'attempt_result' => 1,
            'attempt_completed' => true,
            'attempt_duration' => 'P01DT',
        ]);
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertVerb('http://adlnet.gov/expapi/verbs/completed', 'completed', $output['verb']);
        $this->assertObject('module', $input, $output['context']['contextActivities']['grouping'][1]);
        $this->assertObject('course', $input, $output['context']['contextActivities']['grouping'][0]);
        $this->assertAttempt($input, $output['object']);
        $this->assertEquals($input['attempt_result'], $output['result']['score']['raw']);
        $this->assertEquals($input['attempt_completed'], $output['result']['completion']);
        $this->assertEquals($input['attempt_duration'], $output['result']['duration']);
    }
}
