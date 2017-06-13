<?php namespace LogExpander\Tests;
use \LogExpander\Events\ScormLaunched as Event;

class ScormLaunchedTest extends EventTest {
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event($this->repo);
    }

    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'objecttable' => 'scorm_scoes',
            'objectid' => 1,
            'eventname' => '\mod_scorm\event\sco_launched',
        ]);
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertModule(1, $output['module'], 'scorm');
        $this->assertScorm(1, $output['scorm_scoes']);
    }

    protected function assertScorm($input, $output) {
        $this->assertRecord($input, $output);
    }
}
