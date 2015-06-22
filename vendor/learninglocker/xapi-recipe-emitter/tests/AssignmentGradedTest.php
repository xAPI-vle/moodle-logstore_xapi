<?php namespace Tests;
use \XREmitter\Events\AssignmentGraded as Event;

class AssignmentGradedTest extends EventTest {
    protected static $recipe_name = 'assignment_graded';

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
            $this->constructUser('graded_user'),
            ['grade_result' => 1]
        );
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertVerb('http://www.tincanapi.co.uk/verbs/evaluated', 'evaluated', $output['verb']);
        $this->assertObject('module', $input, $output['context']['contextActivities']['grouping'][1]);
        $this->assertObject('course', $input, $output['context']['contextActivities']['grouping'][0]);
        $this->assertEquals($input['grade_result'], $output['result']['score']['raw']);
        $this->assertEquals(true, $output['result']['completion']);
        $this->assertUser($input, $output['object'], 'graded_user');
    }
}
