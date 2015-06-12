<?php namespace Tests\Xapi;
use \Tests\BaseTest as TestsBase;

abstract class BaseTest extends TestsBase {
    protected function constructCourseViewed() {
        return array_merge(
            $this->constructUser(),
            $this->constructLog(),
            $this->contructObject('course'),
            ['recipe' => 'course_viewed']
        );
    }

    protected function constructModuleViewed() {
        return array_merge(
            $this->constructUser(),
            $this->constructLog(),
            $this->contructObject('course'),
            $this->contructObject('module'),
            ['recipe' => 'module_viewed']
        );
    }

    protected function constructAttemptStarted() {
        return array_merge(
            $this->constructUser(),
            $this->constructLog(),
            $this->contructObject('course'),
            $this->contructObject('module'),
            $this->constructAttempt(),
            ['recipe' => 'attempt_started']
        );
    }

    protected function constructAttemptCompleted() {
        return array_merge(
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
    }

    protected function constructUserLoggedin() {
        return array_merge(
            $this->constructUser(),
            $this->constructLog(),
            $this->contructObject('app'),
            ['recipe' => 'user_loggedin']
        );
    }

    protected function constructUserLoggedout() {
        return array_merge(
            $this->constructUser(),
            $this->constructLog(),
            $this->contructObject('app'),
            ['recipe' => 'user_loggedout']
        );
    }

    protected function constructAssignmentGraded() {
        return array_merge(
            $this->constructUser(),
            $this->constructLog(),
            $this->contructObject('course'),
            $this->contructObject('module'),
            ['recipe' => 'assignment_graded', 'grade_result' => 1]
        );
    }

    protected function constructAssignmentSubmitted() {
        return array_merge(
            $this->constructUser(),
            $this->constructLog(),
            $this->contructObject('course'),
            $this->contructObject('module'),
            ['recipe' => 'assignment_submitted']
        );
    }

    protected function constructUser() {
        return [
            'user_id' => 1,
            'user_url' => 'http://www.example.com/user_url',
            'user_name' => 'Test user_name',
        ];
    }

    protected function constructLog() {
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

    protected function contructObject($type) {
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

    protected function constructAttempt() {
        return [
            'attempt_url' => 'http://www.example.com/attempt_url',
            'attempt_ext' => [
                'test_attempt_ext_key' => 'test_attempt_ext_value',
            ],
            'attempt_ext_key' => 'http://www.example.com/attempt_ext_key',
            'attempt_name' => 'Test attempt_name',
        ];
    }

    protected function assertCourseViewed($test_data, $event) {
        $this->assertUser($test_data, $event['actor']);
        $this->assertVerb('http://id.tincanapi.com/verb/viewed', 'viewed', $event['verb']);
        $this->assertObject('course', $test_data, $event['object']);
        $this->assertLog($test_data, $event);
    }

    protected function assertModuleViewed($test_data, $event) {
        $this->assertUser($test_data, $event['actor']);
        $this->assertVerb('http://id.tincanapi.com/verb/viewed', 'viewed', $event['verb']);
        $this->assertObject('course', $test_data, $event['context']['contextActivities']['grouping'][0]);
        $this->assertObject('module', $test_data, $event['object']);
        $this->assertLog($test_data, $event);
    }

    protected function assertAttemptStarted($test_data, $event) {
        $this->assertUser($test_data, $event['actor']);
        $this->assertVerb('http://activitystrea.ms/schema/1.0/start', 'started', $event['verb']);
        $this->assertObject('course', $test_data, $event['context']['contextActivities']['grouping'][0]);
        $this->assertObject('module', $test_data, $event['context']['contextActivities']['grouping'][1]);
        $this->assertAttempt($test_data, $event['object']);
        $this->assertLog($test_data, $event);
    }

    protected function assertAttemptCompleted($test_data, $event) {
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

    protected function assertUserLoggedin($test_data, $event) {
        $this->assertUser($test_data, $event['actor']);
        $this->assertVerb('https://brindlewaye.com/xAPITerms/verbs/loggedin/', 'logged in to', $event['verb']);
        $this->assertObject('app', $test_data, $event['object']);
        $this->assertLog($test_data, $event);
    }

    protected function assertUserLoggedout($test_data, $event) {
        $this->assertUser($test_data, $event['actor']);
        $this->assertVerb('https://brindlewaye.com/xAPITerms/verbs/loggedout/', 'logged out of', $event['verb']);
        $this->assertObject('app', $test_data, $event['object']);
        $this->assertLog($test_data, $event);
    }

    protected function assertAssignmentGraded($test_data, $event) {
        $this->assertUser($test_data, $event['actor']);
        $this->assertVerb('http://www.tincanapi.co.uk/verbs/evaluated', 'evaluated', $event['verb']);
        $this->assertObject('course', $test_data, $event['context']['contextActivities']['grouping'][0]);
        $this->assertObject('module', $test_data, $event['object']);
        $this->assertLog($test_data, $event);
        $this->assertEquals($test_data['grade_result'], $event['result']['score']['raw']);
        $this->assertEquals(true, $event['result']['completion']);
    }

    protected function assertAssignmentSubmitted($test_data, $event) {
        $this->assertUser($test_data, $event['actor']);
        $this->assertVerb('http://adlnet.gov/expapi/verbs/completed', 'completed', $event['verb']);
        $this->assertObject('course', $test_data, $event['context']['contextActivities']['grouping'][0]);
        $this->assertObject('module', $test_data, $event['object']);
        $this->assertLog($test_data, $event);
    }

    protected function assertUser($test_data, $actual_data) {
        $this->assertEquals($test_data['user_id'], $actual_data['account']['name']);
        $this->assertEquals($test_data['user_url'], $actual_data['account']['homePage']);
        $this->assertEquals($test_data['user_name'], $actual_data['name']);
    }

    protected function assertLog($test_data, $actual_data) {
        $actual_context = $actual_data['context'];
        $this->assertEquals($test_data['context_lang'], $actual_context['language']);
        $this->assertEquals($test_data['context_platform'], $actual_context['platform']);
        $this->assertArrayHasKey($test_data['context_ext_key'], $actual_context['extensions']);
        $this->assertEquals($test_data['context_ext'], $actual_context['extensions'][$test_data['context_ext_key']]);
        $this->assertEquals($test_data['time'], $actual_data['timestamp']);
    }

    protected function assertObject($type, $test_data, $actual_data) {
        $this->assertEquals($test_data[$type.'_url'], $actual_data['id']);
        $this->assertEquals($test_data[$type.'_name'], $actual_data['definition']['name']['en-GB']);
        $this->assertEquals($test_data[$type.'_name'], $actual_data['definition']['name']['en-US']);
        $this->assertEquals($test_data[$type.'_description'], $actual_data['definition']['description']['en-GB']);
        $this->assertEquals($test_data[$type.'_description'], $actual_data['definition']['description']['en-US']);
        $this->assertArrayHasKey($test_data[$type.'_ext_key'], $actual_data['definition']['extensions']);
        $this->assertEquals($test_data[$type.'_ext'], $actual_data['definition']['extensions'][$test_data[$type.'_ext_key']]);
    }

    protected function assertVerb($verb_id, $verb_name, $actual_data) {
        $this->assertEquals($verb_id, $actual_data['id']);
        $this->assertEquals($verb_name, $actual_data['display']['en-GB']);
        $this->assertEquals($verb_name, $actual_data['display']['en-US']);
    }

    protected function assertAttempt($test_data, $actual_data) {
        $this->assertEquals($test_data['attempt_url'], $actual_data['id']);
        $this->assertEquals($test_data['attempt_name'], $actual_data['definition']['name']['en-GB']);
        $this->assertEquals($test_data['attempt_name'], $actual_data['definition']['name']['en-US']);
        $this->assertArrayHasKey($test_data['attempt_ext_key'], $actual_data['definition']['extensions']);
        $this->assertEquals($test_data['attempt_ext'], $actual_data['definition']['extensions'][$test_data['attempt_ext_key']]);
    }
}
