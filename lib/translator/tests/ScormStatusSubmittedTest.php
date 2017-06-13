<?php namespace MXTranslator\Tests;
use \MXTranslator\Events\ScormStatusSubmitted as Event;

class ScormStatusSubmittedTest extends ScormEventTest {
    protected static $recipeName = 'scorm_status_submitted';

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
                'status' => 'completed',
            ],
            'cmi_data' => [
                'cmivalue' => 'completed',
                'cmielement' => 'cmi.core.lesson_status',
                'attemptid' => 2,
            ],
            'scorm_scoes' => $this->constructScormScoes()
        ]);
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertEquals($input['module']->name, $output['scorm_name']);
        $this->assertEquals($input['module']->url, $output['scorm_url']);
        $this->assertEquals($input['scorm_scoes_track']['status'], $output['scorm_status']);
        $this->assertEquals($input['cmi_data']['cmivalue'], $output['scorm_status']);
        $this->assertEquals($input['cmi_data']['attemptid'], $output['scorm_attempt']);
    }
}
