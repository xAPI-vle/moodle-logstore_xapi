<?php namespace LogExpander\Tests;
use \LogExpander\Events\FacetofaceAttended as Event;

class FacetofaceAttendedTest extends FacetofaceEventTest {
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event($this->repo);
    }

    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'objecttable' => 'facetoface_sessions',
            'objectid' => 1,
            'eventname' => '\mod_facetoface\event\signup_success',
        ]);
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertEquals(1, $output['signups'][1]->id);
        $this->assertEquals(1, $output['signups'][1]->attendee->id);
    }
}
