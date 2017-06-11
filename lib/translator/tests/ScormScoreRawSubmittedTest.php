<?php namespace MXTranslator\Tests;
use \MXTranslator\Events\ScormScoreRawSubmitted as Event;

class ScormScoreRawSubmittedTest extends ScormEventTest {
    protected static $recipe_name = 'scorm_scoreraw_submitted';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'scorm_scoes_track' => [
                'scoremax' => 100,
                'scoremin' => 0,
                'status' => 'status',
            ],
            'cmi_data' => [
                'cmivalue' => 100,
                'cmielement' => 'cmi.core.score.raw',
                'attemptid' => 1,
            ],
            'scorm_scoes' => $this->constructScormScoes()
        ]);
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertEquals($input['module']->name, $output['scorm_name']);
        $this->assertEquals($input['module']->url, $output['scorm_url']);
        $this->assertEquals($input['module']->url, $output['scorm_scoes_url']);
        $this->assertEquals($input['scorm_scoes']->title, $output['scorm_scoes_name']);
        $this->assertEquals($input['scorm_scoes']->id, $output['scorm_scoes_id']);
        $this->assertEquals($input['scorm_scoes_track']['scoremin'], $output['scorm_score_min']);
        $this->assertEquals($input['scorm_scoes_track']['scoremax'], $output['scorm_score_max']);
        $this->assertEquals($input['scorm_scoes_track']['status'], $output['scorm_status']);
        $this->assertEquals($input['cmi_data']['attemptid'], $output['scorm_attempt']);
    }
}
