<?php namespace LogExpander\Tests;
use \LogExpander\Events\AssignmentSubmitted as Event;

class AssignmentSubmittedTest extends EventTest {
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event($this->repo);
    }

    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'objecttable' => 'assign_submission',
            'objectid' => 1,
            'eventname' => '\mod_assign\event\assessable_submitted',
        ]);
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertModule(1, $output['module'], 'assign');
        $this->assertRecord($input['objectid'], $output['submission']);
    }
}
