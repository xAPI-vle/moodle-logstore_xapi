<?php namespace LogExpander\Tests;
use \LogExpander\Events\ModuleEvent as Event;

class ModuleEventTest extends EventTest {
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event($this->repo);
    }

    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'objecttable' => 'page',
            'objectid' => 1,
            'eventname' => '\mod_page\event\course_module_viewed',
        ]);
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertModule($input['objectid'], $output['module'], 'page');
    }
}
