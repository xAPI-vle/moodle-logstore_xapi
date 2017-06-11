<?php namespace XREmitter\Tests;
use \XREmitter\Events\Attended as Event;

class AttendedTest extends EventTest {
    protected static $recipeName = 'training_session_attend';

    /**
     * Sets up the tests.
     * @override EventTest
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function constructInput() {
        return array_merge(
            parent::constructInput(),
            $this->contructObject('course'),
            $this->contructObject('module'),
            $this->contructObject('session', 'http://activitystrea.ms/schema/1.0/event'),
            $this->constructUser('attendee'),
            [
                "attempt_duration" => "PT150S",
                "attempt_completion" => true
            ]
        );
    }

    protected function assertOutput($input, $output) {
        $this->assertObject('app', $input, $output['context']['contextActivities']['grouping'][0]);
        $this->assertObject('source', $input, $output['context']['contextActivities']['category'][0]);
        $this->assertLog($input, $output);
        $this->assertInfo(
            $input['context_info'],
            $output['context']['extensions']['http://lrs.learninglocker.net/define/extensions/info']
        );
        $this->assertValidXapiStatement($output);
        $this->assertUser($input, $output['actor'], 'attendee');
        $this->assertUser($input, $output['context']['instructor'], 'user');
        $this->assertVerb('http://adlnet.gov/expapi/verbs/attended', 'attended', $output['verb']);
        $this->assertObject('session', $input, $output['object']);
        $this->assertObject('module', $input, $output['context']['contextActivities']['parent'][0]);
        $this->assertObject('course', $input, $output['context']['contextActivities']['grouping'][1]);
        $this->assertEquals($input['attempt_duration'], $output['result']['duration']);
        $this->assertEquals($input['attempt_completion'], $output['result']['completion']);
        $this->assertEquals('http://xapi.trainingevidencesystems.com/recipes/attendance/0_0_1#simple', $output['context']['contextActivities']['category'][1]['id']);
    }
}
