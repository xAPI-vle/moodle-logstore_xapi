<?php namespace XREmitter\Tests;
use \XREmitter\Events\AttemptCompleted as Event;

class AttemptCompletedTest extends EventTest {
    protected static $recipeName = 'attempt_completed';

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

    protected function constructAttempt() {
        return array_merge(parent::constructAttempt(), [
            'attempt_score_raw' => 1,
            'attempt_score_min' => 0,
            'attempt_score_max' => 5,
            'attempt_score_scaled' => 0.2,
            'attempt_success' => false,
            'attempt_completed' => true,
            'attempt_duration' => 'P01DT',
        ]);
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertVerb('http://adlnet.gov/expapi/verbs/completed', 'completed', $output['verb']);
        $this->assertAttempt($input, $output['context']['contextActivities']['grouping'][2]);
        $this->assertObject('course', $input, $output['context']['contextActivities']['grouping'][1]);
        $this->assertObject('module', $input, $output['object']);
        $this->assertEquals($input['attempt_score_raw'], $output['result']['score']['raw']);
        $this->assertEquals($input['attempt_score_min'], $output['result']['score']['min']);
        $this->assertEquals($input['attempt_score_max'], $output['result']['score']['max']);
        $this->assertEquals($input['attempt_score_scaled'], $output['result']['score']['scaled']);
        $this->assertEquals($input['attempt_success'], $output['result']['success']);
        $this->assertEquals($input['attempt_completed'], $output['result']['completion']);
        $this->assertEquals($input['attempt_duration'], $output['result']['duration']);
    }
}
