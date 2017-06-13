<?php namespace XREmitter\Tests;
use \XREmitter\Events\ScormScoreRawSubmitted as Event;

class ScormScoreRawSubmittedTest extends ScormEventTest {
    protected static $recipeName = 'scorm_scoreraw_submitted';

    /**
     * Sets up the tests.
     * @override EventTest
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertVerb('http://adlnet.gov/expapi/verbs/completed', 'completed', $output['verb']);
        $this->assertEquals($input['scorm_score_raw'], $output['result']['score']['raw']);
        $this->assertEquals($input['scorm_score_min'], $output['result']['score']['min']);
        $this->assertEquals($input['scorm_score_max'], $output['result']['score']['max']);
        $this->assertEquals($input['scorm_score_scaled'], $output['result']['score']['scaled']);
        $this->assertEquals($input['scorm_status'], $output['verb']['display']['en']);
    }
}
