<?php namespace LogExpander\Tests;
use \LogExpander\Events\ScormSubmitted as Event;

class ScormSubmittedTest extends EventTest {
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event($this->repo);
    }

    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'objecttable' => 'scorm_scoes_track',
            'objectid' => 1,
            'contextinstanceid' => 1,
            'eventname' => '\mod_scorm\event\scoreraw_submitted',
            'other' => 'a:3:{s:9:"attemptid";i:1;s:10:"cmielement";s:18:"cmi.core.score.raw";s:8:"cmivalue";s:3:"100";}',
        ]);
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertModule(1, $output['module'], 'scorm');
        $this->assertEquals(1, $output['scorm_scoes']->id);
        $this->assertScormScoesTrack($output);
        $this->assertCmiData($output);
    }

    protected function assertScormScoesTrack($output) {
        $this->assertEquals('status', $output['scorm_scoes_track']['status']);
        $this->assertEquals(100, $output['scorm_scoes_track']['scoremax']);
        $this->assertEquals(0, $output['scorm_scoes_track']['scoremin']);
    }

    protected function assertCmiData($output) {
        $this->assertEquals(1, $output['cmi_data']['attemptid']);
        $this->assertEquals('cmi.core.score.raw', $output['cmi_data']['cmielement']);
        $this->assertEquals(100, $output['cmi_data']['cmivalue']);
    }
}
