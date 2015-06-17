<?php namespace Tests;
use \LogExpander\Events\AttemptEvent as Event;

class AttemptEventTest extends EventTest {
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event($this->repo);
    }

    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'objecttable' => 'quiz_attempts',
            'objectid' => 1,
            'eventname' => '\mod_quiz\event\attempt_preview_started',
        ]);
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertModule(1, $output['module'], 'quiz');
        $this->assertAttempt($input['objectid'], $output['attempt']);
    }
}
