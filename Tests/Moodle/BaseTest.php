<?php namespace Tests\Moodle;
use \Tests\BaseTest as TestsBase;

abstract class BaseTest extends TestsBase {
    protected $cfg;

    public function __construct() {
        $this->cfg = (object) [
            'wwwroot' => 'http://www.example.com'
        ];
    }

    protected function constructCourseViewed() {
        return [
            'userid' => 1,
            'courseid' => 1,
            'timecreated' => 1433946701,
            'eventname' => '\core\event\course_viewed',
        ];
    }

    protected function constructPageViewed() {
        return array_merge($this->constructCourseViewed(), [
            'objecttable' => 'page',
            'objectid' => 1,
            'eventname' => '\mod_page\event\course_module_viewed',
        ]);
    }

    protected function constructQuizViewed() {
        return array_merge($this->constructCourseViewed(), [
            'objecttable' => 'quiz',
            'objectid' => 1,
            'eventname' => '\mod_quiz\event\course_module_viewed',
        ]);
    }

    protected function constructUrlViewed() {
        return array_merge($this->constructCourseViewed(), [
            'objecttable' => 'url',
            'objectid' => 1,
            'eventname' => '\mod_url\event\course_module_viewed',
        ]);
    }

    protected function constructFolderViewed() {
        return array_merge($this->constructCourseViewed(), [
            'objecttable' => 'folder',
            'objectid' => 1,
            'eventname' => '\mod_folder\event\course_module_viewed',
        ]);
    }

    protected function constructBookViewed() {
        return array_merge($this->constructCourseViewed(), [
            'objecttable' => 'book',
            'objectid' => 1,
            'eventname' => '\mod_book\event\course_module_viewed',
        ]);
    }

    protected function constructAttemptStarted() {
        return array_merge($this->constructCourseViewed(), [
            'objecttable' => 'quiz_attempts',
            'objectid' => 1,
            'eventname' => '\mod_quiz\event\attempt_preview_started',
        ]);
    }

    protected function constructAttemptReviewed() {
        return array_merge($this->constructAttemptStarted(), [
            'eventname' => '\mod_quiz\event\attempt_reviewed',
        ]);
    }

    protected function constructUserLoggedin() {
        return array_merge($this->constructCourseViewed(), [
            'eventname' => '\core\event\user_loggedin',
        ]);
    }

    protected function constructUserLoggedout() {
        return array_merge($this->constructCourseViewed(), [
            'eventname' => '\core\event\user_loggedout',
        ]);
    }

    protected function constructAssignmentGraded() {
        return array_merge($this->constructCourseViewed(), [
            'objecttable' => 'assign_grades',
            'objectid' => 1,
            'eventname' => '\mod_assign\event\submission_graded',
        ]);
    }

    protected function constructAssignmentSubmitted() {
        return array_merge($this->constructCourseViewed(), [
            'objecttable' => 'assign_submission',
            'objectid' => 1,
            'eventname' => '\mod_assign\event\assessable_submitted',
        ]);
    }

    // TODO: Done some of this, but not all!!!
    protected function assertCourseViewed($test_data, $event) {
        $this->assertUser($test_data['userid'], $event['user']);
        $this->assertCourse($test_data['courseid'], $event['course']);
        $this->assertEquals($test_data, $event['event']);
    }

    protected function assertModuleViewed($test_data, $event) {
        $this->assertUser($test_data['userid'], $event['user']);
        $this->assertCourse($test_data['courseid'], $event['course']);
        $this->assertModule($test_data['objectid'], $event['module'], 'page');
        $this->assertEquals($test_data, $event['event']);
    }

    protected function assertAttemptStarted($test_data, $event) {
        $this->assertUser($test_data['userid'], $event['user']);
        $this->assertCourse($test_data['courseid'], $event['course']);
        $this->assertModule(1, $event['module'], 'quiz');
        $this->assertAttempt($test_data['objectid'], $event['attempt']);
        $this->assertEquals($test_data, $event['event']);
    }

    protected function assertUserLoggedin($test_data, $event) {
        $this->assertUser($test_data['userid'], $event['user']);
        $this->assertCourse($test_data['courseid'], $event['course']);
        $this->assertEquals($test_data, $event['event']);
    }

    protected function assertUserLoggedout($test_data, $event) {
        $this->assertUser($test_data['userid'], $event['user']);
        $this->assertCourse($test_data['courseid'], $event['course']);
        $this->assertEquals($test_data, $event['event']);
    }

    protected function assertAssignmentGraded($test_data, $event) {
        $this->assertUser($test_data['userid'], $event['user']);
        $this->assertCourse($test_data['courseid'], $event['course']);
        $this->assertModule(1, $event['module'], 'assign');
        $this->assertRecord($test_data['objectid'], $event['grade']);
        $this->assertEquals($test_data, $event['event']);
    }

    protected function assertAssignmentSubmitted($test_data, $event) {
        $this->assertUser($test_data['userid'], $event['user']);
        $this->assertCourse($test_data['courseid'], $event['course']);
        $this->assertModule(1, $event['module'], 'assign');
        $this->assertRecord($test_data['objectid'], $event['submission']);
        $this->assertEquals($test_data, $event['event']);
    }

    protected function assertRecord($test_data, $actual_data) {
        $this->assertEquals($test_data, $actual_data->id);
    }

    protected function assertUser($test_data, $actual_data) {
        $this->assertRecord($test_data, $actual_data);
        $this->assertEquals($this->cfg->wwwroot, $actual_data->url);
    }

    protected function assertCourse($test_data, $actual_data) {
        $this->assertRecord($test_data, $actual_data);
        $this->assertEquals($this->cfg->wwwroot . '/course.php?id=' . $actual_data->id, $actual_data->url);
    }

    protected function assertModule($test_data, $actual_data, $type) {
        $this->assertRecord($test_data, $actual_data);
        $this->assertEquals($this->cfg->wwwroot . '/mod/'.$type.'/view.php?id=' . $actual_data->id, $actual_data->url);
    }

    protected function assertAttempt($test_data, $actual_data) {
        $this->assertRecord($test_data, $actual_data);
        $this->assertEquals($this->cfg->wwwroot . '/mod/quiz/attempt.php?attempt=' . $actual_data->id, $actual_data->url);
    }
}
