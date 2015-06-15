<?php namespace Tests\Translator;
use \Tests\BaseTest as TestsBase;

abstract class BaseTest extends TestsBase {
    protected function constructCourseViewed() {
        return [
            'user' => $this->constructUser(),
            'course' => $this->constructCourse(),
            'event' => $this->constructEvent('\core\event\course_viewed'),
        ];
    }

    protected function constructPageViewed() {
        return array_merge($this->constructCourseViewed(), [
            'module' => $this->constructModule(),
            'event' => $this->constructEvent('\mod_page\event\course_module_viewed'),
        ]);
    }

    protected function constructQuizViewed() {
        return array_merge($this->constructPageViewed(), [
            'event' => $this->constructEvent('\mod_quiz\event\course_module_viewed'),
        ]);
    }

    protected function constructUrlViewed() {
        return array_merge($this->constructPageViewed(), [
            'event' => $this->constructEvent('\mod_url\event\course_module_viewed'),
        ]);
    }

    protected function constructFolderViewed() {
        return array_merge($this->constructPageViewed(), [
            'event' => $this->constructEvent('\mod_folder\event\course_module_viewed'),
        ]);
    }

    protected function constructBookViewed() {
        return array_merge($this->constructPageViewed(), [
            'event' => $this->constructEvent('\mod_book\event\course_module_viewed'),
        ]);
    }

    protected function constructAttemptStarted() {
        return array_merge($this->constructPageViewed(), [
            'attempt' => $this->constructAttempt(),
            'event' => $this->constructEvent('\mod_quiz\event\attempt_preview_started'),
        ]);
    }

    protected function constructAttemptReviewed() {
        return array_merge($this->constructAttemptStarted(), [
            'event' => $this->constructEvent('\mod_quiz\event\attempt_reviewed'),
        ]);
    }

    protected function constructUserLoggedin() {
        return array_merge($this->constructCourseViewed(), [
            'event' => $this->constructEvent('\core\event\user_loggedin'),
        ]);
    }

    protected function constructUserLoggedout() {
        return array_merge($this->constructCourseViewed(), [
            'event' => $this->constructEvent('\core\event\user_loggedout'),
        ]);
    }

    protected function constructAssignmentGraded() {
        return array_merge($this->constructPageViewed(), [
            'grade' => (object) [
                'grade' => 1,
            ],
            'event' => $this->constructEvent('\mod_assign\event\submission_graded'),
        ]);
    }

    protected function constructAssignmentSubmitted() {
        return array_merge($this->constructPageViewed(), [
            'submission' => (object) [],
            'event' => $this->constructEvent('\mod_assign\event\assessable_submitted'),
        ]);
    }

    protected function constructUser() {
        return (object) [
            'id' => 1,
            'url' => 'http://www.example.com/user_url',
            'username' => 'Test user_name',
        ];
    }

    protected function constructEvent($event_name) {
        return [
            'eventname' => $event_name,
            'timecreated' => 1433946701,
        ];
    }

    protected function constructCourse() {
        return (object) [
            'url' => 'http://www.example.com/course_url',
            'fullname' => 'Test course_fullname',
            'summary' => 'Test course_summary',
            'lang' => 'en',
        ];
    }

    protected function constructModule() {
        return (object) [
            'url' => 'http://www.example.com/module_url',
            'name' => 'Test module_name',
            'intro' => 'Test module_intro',
        ];
    }

    protected function constructAttempt() {
        return (object) [
            'url' => 'http://www.example.com/attempt_url',
            'name' => 'Test attempt_name',
            'timestart' => 1433946701,
            'timefinish' => 1433946701,
            'sumgrades' => 1,
            'state' => 'finished',
        ];
    }

    protected function assertCourseViewed($test_data, $event) {
        $this->assertUser($test_data['user'], $event);
        $this->assertCourse('course', $test_data['course'], $event);
        $this->assertEvent($test_data['event'], $event);
        $this->assertEquals('course_viewed', $event['recipe']);
    }

    protected function assertModuleViewed($test_data, $event) {
        $this->assertUser($test_data['user'], $event);
        $this->assertCourse('course', $test_data['course'], $event);
        $this->assertModule('module', $test_data['module'], $event);
        $this->assertEvent($test_data['event'], $event);
        $this->assertEquals('module_viewed', $event['recipe']);
    }

    protected function assertAttemptStarted($test_data, $event) {
        $this->assertUser($test_data['user'], $event);
        $this->assertCourse('course', $test_data['course'], $event);
        $this->assertModule('module', $test_data['module'], $event);
        $this->assertEvent($test_data['event'], $event);
        $this->assertAttempt($test_data['attempt'], $event);
        $this->assertEquals('attempt_started', $event['recipe']);
    }

    protected function assertAttemptReviewed($test_data, $event) {
        $this->assertUser($test_data['user'], $event);
        $this->assertCourse('course', $test_data['course'], $event);
        $this->assertModule('module', $test_data['module'], $event);
        $this->assertEvent($test_data['event'], $event);
        $this->assertEquals('attempt_completed', $event['recipe']);
        $attempt = $test_data['attempt'];
        $this->assertAttempt($attempt, $event);
        $this->assertEquals((float) $attempt->sumgrades, $event['attempt_result']);
        $this->assertEquals($attempt->state === 'finished', $event['attempt_completed']);
    }

    protected function assertUserLoggedin($test_data, $event) {
        $this->assertUser($test_data['user'], $event);
        $this->assertCourse('app', $test_data['course'], $event);
        $this->assertEvent($test_data['event'], $event);
        $this->assertEquals('user_loggedin', $event['recipe']);
    }

    protected function assertUserLoggedout($test_data, $event) {
        $this->assertUser($test_data['user'], $event);
        $this->assertCourse('app', $test_data['course'], $event);
        $this->assertEvent($test_data['event'], $event);
        $this->assertEquals('user_loggedout', $event['recipe']);
    }

    protected function assertAssignmentGraded($test_data, $event) {
        $this->assertUser($test_data['user'], $event);
        $this->assertCourse('course', $test_data['course'], $event);
        $this->assertModule('module', $test_data['module'], $event);
        $this->assertEvent($test_data['event'], $event);
        $this->assertEquals($test_data['grade']->grade, $event['grade_result']);
        $this->assertEquals('assignment_graded', $event['recipe']);
    }

    protected function assertAssignmentSubmitted($test_data, $event) {
        $this->assertUser($test_data['user'], $event);
        $this->assertCourse('course', $test_data['course'], $event);
        $this->assertModule('module', $test_data['module'], $event);
        $this->assertEvent($test_data['event'], $event);
        $this->assertEquals('assignment_submitted', $event['recipe']);
    }

    protected function assertUser($test_data, $actual_data) {
        $this->assertEquals($test_data->id, $actual_data['user_id']);
        $this->assertEquals($test_data->url, $actual_data['user_url']);
        $this->assertEquals($test_data->username, $actual_data['user_name']);
    }

    protected function assertEvent($test_data, $actual_data) {
        $ext_key = 'http://lrs.learninglocker.net/define/extensions/moodle_logstore_standard_log';
        $this->assertEquals('Moodle', $actual_data['context_platform']);
        $this->assertEquals($test_data, $actual_data['context_ext']);
        $this->assertEquals($ext_key, $actual_data['context_ext_key']);
        $this->assertEquals(date('c', $test_data['timecreated']), $actual_data['time']);
    }

    protected function assertCourse($type, $test_data, $actual_data) {
        $ext_key = 'http://lrs.learninglocker.net/define/extensions/moodle_course';
        $this->assertEquals($test_data->lang, $actual_data['context_lang']);
        $this->assertEquals($test_data->url, $actual_data[$type.'_url']);
        $this->assertEquals($test_data->fullname, $actual_data[$type.'_name']);
        $this->assertEquals($test_data->summary, $actual_data[$type.'_description']);
        $this->assertEquals($test_data, $actual_data[$type.'_ext']);
        $this->assertEquals($ext_key, $actual_data[$type.'_ext_key']);
    }

    protected function assertModule($type, $test_data, $actual_data) {
        $ext_key = 'http://lrs.learninglocker.net/define/extensions/moodle_module';
        $this->assertEquals($test_data->url, $actual_data[$type.'_url']);
        $this->assertEquals($test_data->name, $actual_data[$type.'_name']);
        $this->assertEquals($test_data->intro, $actual_data[$type.'_description']);
        $this->assertEquals($test_data, $actual_data[$type.'_ext']);
        $this->assertEquals($ext_key, $actual_data[$type.'_ext_key']);
    }

    protected function assertAttempt($test_data, $actual_data) {
        $ext_key = 'http://lrs.learninglocker.net/define/extensions/moodle_attempt';
        $this->assertEquals($test_data->url, $actual_data['attempt_url']);
        $this->assertEquals($test_data->name, $actual_data['attempt_name']);
        $this->assertEquals($test_data, $actual_data['attempt_ext']);
        $this->assertEquals($ext_key, $actual_data['attempt_ext_key']);
    }
}
