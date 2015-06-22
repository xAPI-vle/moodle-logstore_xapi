<?php namespace Tests;
use \LogExpander\Events\AssignmentGraded as Event;

class AssignmentGradedTest extends EventTest {
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event($this->repo);
    }

    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'objecttable' => 'assign_grades',
            'objectid' => 1,
            'eventname' => '\mod_assign\event\submission_graded',
        ]);
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertModule(1, $output['module'], 'assign');
        $this->assertUser(1, $output['graded_user']);
        $this->assertRecord($input['objectid'], $output['grade']);
    }
}
