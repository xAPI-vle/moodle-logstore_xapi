<?php namespace Tests\Xapi;
use \Tests\BaseTest as TestCase;
use \logstore_emitter\xapi\repository as xapi_repository;
use \logstore_emitter\xapi\service as xapi_service;

class ServiceTest extends TestCase {
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->service = new xapi_service(new xapi_repository(new TestRemoteLrs('', '1.0.1', '', '')));
    }

    /**
     * Tests the read_course_viewed_event method of the xapi_service.
     */
    public function testReadCourseViewedEvent() {
        $test_data = array_merge(
            $this->constructUser(),
            $this->constructLog(),
            $this->contructObject('course'),
            ['recipe' => 'course_viewed']
        );
        $event = $this->service->read_course_viewed_event($test_data);

        $this->assertUser($test_data, $event['actor']);
        $this->assertVerb('http://id.tincanapi.com/verb/viewed', 'viewed', $event['verb']);
        $this->assertObject('course', $test_data, $event['object']);
        $this->assertLog($test_data, $event);
    }

    /**
     * Tests the read_module_viewed_event method of the xapi_service.
     */
    public function testReadModuleViewedEvent() {
        $test_data = array_merge(
            $this->constructUser(),
            $this->constructLog(),
            $this->contructObject('course'),
            $this->contructObject('module'),
            ['recipe' => 'module_viewed']
        );
        $event = $this->service->read_module_viewed_event($test_data);

        $this->assertUser($test_data, $event['actor']);
        $this->assertVerb('http://id.tincanapi.com/verb/viewed', 'viewed', $event['verb']);
        $this->assertObject('course', $test_data, $event['context']['contextActivities']['grouping'][0]);
        $this->assertObject('module', $test_data, $event['object']);
        $this->assertLog($test_data, $event);
    }

    /**
     * Tests the read_attempt_started_event method of the xapi_service.
     */
    public function testReadAttemptStartedEvent() {
        $test_data = array_merge(
            $this->constructUser(),
            $this->constructLog(),
            $this->contructObject('course'),
            $this->contructObject('module'),
            $this->constructAttempt(),
            ['recipe' => 'attempt_started']
        );
        $event = $this->service->read_attempt_started_event($test_data);

        $this->assertUser($test_data, $event['actor']);
        $this->assertVerb('http://activitystrea.ms/schema/1.0/start', 'started', $event['verb']);
        $this->assertObject('course', $test_data, $event['context']['contextActivities']['grouping'][0]);
        $this->assertObject('module', $test_data, $event['context']['contextActivities']['grouping'][1]);
        $this->assertAttempt($test_data, $event['object']);
        $this->assertLog($test_data, $event);
    }

    /**
     * Tests the attempt_completed method of the xapi_service.
     */
    public function testReadAttemptCompletedEvent() {
        $test_data = array_merge(
            $this->constructUser(),
            $this->constructLog(),
            $this->contructObject('course'),
            $this->contructObject('module'),
            $this->constructAttempt(),
            [
                'recipe' => 'attempt_completed',
                'attempt_result' => 1,
                'attempt_completed' => true,
                'attempt_duration' => 'P01DT',
            ]
        );
        $event = $this->service->read_attempt_completed_event($test_data);

        $this->assertUser($test_data, $event['actor']);
        $this->assertVerb('http://adlnet.gov/expapi/verbs/completed', 'completed', $event['verb']);
        $this->assertObject('course', $test_data, $event['context']['contextActivities']['grouping'][0]);
        $this->assertObject('module', $test_data, $event['context']['contextActivities']['grouping'][1]);
        $this->assertAttempt($test_data, $event['object']);
        $this->assertLog($test_data, $event);
        $this->assertEquals($test_data['attempt_result'], $event['result']['score']['raw']);
        $this->assertEquals($test_data['attempt_completed'], $event['result']['completion']);
        $this->assertEquals($test_data['attempt_duration'], $event['result']['duration']);
    }

    /**
     * Tests the user_loggedin method of the xapi_service.
     */
    public function testReadUserLoggedinEvent() {
        $test_data = array_merge(
            $this->constructUser(),
            $this->constructLog(),
            $this->contructObject('app'),
            ['recipe' => 'user_loggedin']
        );
        $event = $this->service->read_user_loggedin_event($test_data);

        $this->assertUser($test_data, $event['actor']);
        $this->assertVerb('https://brindlewaye.com/xAPITerms/verbs/loggedin/', 'logged in to', $event['verb']);
        $this->assertObject('app', $test_data, $event['object']);
        $this->assertLog($test_data, $event);
    }

    private function constructUser() {
        return [
            'user_id' => 1,
            'user_url' => 'http://www.example.com/user_url',
            'user_name' => 'Test user_name',
        ];
    }

    private function constructLog() {
        return [
            'context_lang' => 'en',
            'context_platform' => 'Moodle',
            'context_ext' => [
                'test_context_ext_key' => 'test_context_ext_value',
            ],
            'context_ext_key' => 'http://www.example.com/context_ext_key',
            'time' => '2015-01-01T01:00Z',
        ];
    }

    private function contructObject($type) {
        return [
            $type.'_url' => 'http://www.example.com/'.$type.'_url',
            $type.'_name' => 'Test '.$type.'_name',
            $type.'_description' => 'Test '.$type.'_description',
            $type.'_ext' => [
                'test_'.$type.'_ext_key' => 'test_'.$type.'_ext_value',
            ],
            $type.'_ext_key' => 'http://www.example.com/'.$type.'_ext_key',
        ];
    }

    private function constructAttempt() {
        return [
            'attempt_url' => 'http://www.example.com/attempt_url',
            'attempt_ext' => [
                'test_attempt_ext_key' => 'test_attempt_ext_value',
            ],
            'attempt_ext_key' => 'http://www.example.com/attempt_ext_key',
            'attempt_name' => 'Test attempt_name',
        ];
    }

    private function assertUser($test_data, $actual_data) {
        $this->assertEquals($test_data['user_id'], $actual_data['account']['name']);
        $this->assertEquals($test_data['user_url'], $actual_data['account']['homePage']);
        $this->assertEquals($test_data['user_name'], $actual_data['name']);
    }

    private function assertLog($test_data, $actual_data) {
        $actual_context = $actual_data['context'];
        $this->assertEquals($test_data['context_lang'], $actual_context['language']);
        $this->assertEquals($test_data['context_platform'], $actual_context['platform']);
        $this->assertArrayHasKey($test_data['context_ext_key'], $actual_context['extensions']);
        $this->assertEquals($test_data['context_ext'], $actual_context['extensions'][$test_data['context_ext_key']]);
        $this->assertEquals($test_data['time'], $actual_data['timestamp']);
    }

    private function assertObject($type, $test_data, $actual_data) {
        $this->assertEquals($test_data[$type.'_url'], $actual_data['id']);
        $this->assertEquals($test_data[$type.'_name'], $actual_data['definition']['name']['en-GB']);
        $this->assertEquals($test_data[$type.'_name'], $actual_data['definition']['name']['en-US']);
        $this->assertEquals($test_data[$type.'_description'], $actual_data['definition']['description']['en-GB']);
        $this->assertEquals($test_data[$type.'_description'], $actual_data['definition']['description']['en-US']);
        $this->assertArrayHasKey($test_data[$type.'_ext_key'], $actual_data['definition']['extensions']);
        $this->assertEquals($test_data[$type.'_ext'], $actual_data['definition']['extensions'][$test_data[$type.'_ext_key']]);
    }

    private function assertVerb($verb_id, $verb_name, $actual_data) {
        $this->assertEquals($verb_id, $actual_data['id']);
        $this->assertEquals($verb_name, $actual_data['display']['en-GB']);
        $this->assertEquals($verb_name, $actual_data['display']['en-US']);
    }

    private function assertAttempt($test_data, $actual_data) {
        $this->assertEquals($test_data['attempt_url'], $actual_data['id']);
        $this->assertEquals($test_data['attempt_name'], $actual_data['definition']['name']['en-GB']);
        $this->assertEquals($test_data['attempt_name'], $actual_data['definition']['name']['en-US']);
        $this->assertArrayHasKey($test_data['attempt_ext_key'], $actual_data['definition']['extensions']);
        $this->assertEquals($test_data['attempt_ext'], $actual_data['definition']['extensions'][$test_data['attempt_ext_key']]);
    }
}

/*
read_course_viewed_event
read_module_viewed_event
read_attempt_started_event
read_attempt_completed_event
read_user_loggedin_event
read_user_loggedout_event
read_assignment_graded_event
read_assignment_submitted_event
create_event
 */