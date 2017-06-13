<?php namespace LogExpander\Tests;
use \LogExpander\Events\FacetofaceEvent as Event;

class FacetofaceEventTest extends EventTest {
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
        $this->assertModule(1, $output['module'], 'facetoface');
        $this->assertEquals(2, $output['session']->dates[2]->id);
    }
}
