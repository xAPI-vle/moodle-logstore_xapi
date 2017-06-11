<?php namespace XREmitter\Tests;
use \XREmitter\Events\EventUnenrol as Event;

class EventUnenrolTest extends EventTest {
    protected static $recipeName = 'training_session_unenrol';

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
            $this->contructObject('session', 'http://activitystrea.ms/schema/1.0/event')
        );
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertUser($input, $output['actor'], 'user');
        $this->assertVerb('http://id.tincanapi.com/verb/unregistered', 'unregistered from', $output['verb']);
        $this->assertObject('session', $input, $output['object']);
        $this->assertObject('module', $input, $output['context']['contextActivities']['parent'][0]);
        $this->assertObject('course', $input, $output['context']['contextActivities']['grouping'][1]);
        $this->assertEquals('http://xapi.trainingevidencesystems.com/recipes/attendance/0_0_1#detailed', $output['context']['contextActivities']['category'][1]['id']);
    }
}
