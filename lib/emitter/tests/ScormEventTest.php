<?php namespace XREmitter\Tests;
use \XREmitter\Events\ScormEvent as Event;

class ScormEventTest extends EventTest {
    protected static $recipeName = 'scorm_event';

    /**
     * Sets up the tests.
     * @override ModuleViewedTest
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function constructInput() {
        return array_merge(
            parent::constructInput(),
            $this->contructObject('course'),
            $this->contructObject('module'),
            $this->constructScormTracking(),
            $this->constructScormScoes()
        );
    }

    protected function constructScormTracking() {
        return [
            'scorm_score_raw' => 100,
            'scorm_score_min' => 0,
            'scorm_score_scaled' => 1,
            'scorm_score_max' => 100,
            'scorm_status' => 'completed',
        ];
    }

    protected function constructScormScoes() {
        return [
            'scorm_scoes_id' =>  1,
            'scorm_scoes_url' =>  'http://www.example.com/module_url',
            'scorm_scoes_type' => static::$xapiType. 'sco',
            'scorm_scoes_name' => 'Sco name',
            'scorm_scoes_description' => 'Sco Description',
        ];
    }

    protected function assertOutput($input, $output) {
        $this->assertUser($input, $output['actor'], 'user');
        $this->assertObject('course', $input, $output['context']['contextActivities']['grouping'][0]);
        $this->assertObject('module', $input, $output['object']);
        $this->assertObject('scorm_scoes', $input, $output['context']['contextActivities']['grouping'][1]);
    }

}
