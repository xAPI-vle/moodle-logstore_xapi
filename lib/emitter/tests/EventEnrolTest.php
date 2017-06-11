<?php namespace XREmitter\Tests;
use \XREmitter\Events\EventEnrol as Event;

class EventEnrolTest extends EventTest {
    protected static $recipe_name = 'training_session_enrol';

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
        $this->assertVerb('http://adlnet.gov/expapi/verbs/registered', 'registered for', $output['verb']);
        $this->assertObject('session', $input, $output['object']);
        $this->assertObject('module', $input, $output['context']['contextActivities']['parent'][0]);
        $this->assertObject('course', $input, $output['context']['contextActivities']['grouping'][1]);
        $this->assertEquals('http://xapi.trainingevidencesystems.com/recipes/attendance/0_0_1#detailed', $output['context']['contextActivities']['category'][1]['id']);
    }
}
