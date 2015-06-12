<?php namespace Tests\Xapi;
use \logstore_emitter\xapi\service as xapi_service;

class ServiceTest extends BaseTest {
    /**
     * Sets up the tests.
     * @override BaseTest
     */
    public function setup() {
        $this->service = new xapi_service(new TestRepository(new TestRemoteLrs('', '1.0.1', '', '')));
    }

    /**
     * Tests the create_event method of the xapi_service.
     */
    public function testCreateEvent() {
        $test_data = [];
        $event = $this->service->create_event($test_data);
        $this->assertEquals($test_data, $event);
    }

    /**
     * Tests the read_course_viewed_event method of the xapi_service.
     */
    public function testReadCourseViewedEvent() {
        $test_data = $this->constructCourseViewed();
        $event = $this->service->read_course_viewed_event($test_data);
        $this->assertCourseViewed($test_data, $event);
    }

    /**
     * Tests the read_module_viewed_event method of the xapi_service.
     */
    public function testReadModuleViewedEvent() {
        $test_data = $this->constructModuleViewed();
        $event = $this->service->read_module_viewed_event($test_data);
        $this->assertModuleViewed($test_data, $event);
    }

    /**
     * Tests the read_attempt_started_event method of the xapi_service.
     */
    public function testReadAttemptStartedEvent() {
        $test_data = $this->constructAttemptStarted();
        $event = $this->service->read_attempt_started_event($test_data);
        $this->assertAttemptStarted($test_data, $event);
    }

    /**
     * Tests the read_attempt_completed_event method of the xapi_service.
     */
    public function testReadAttemptCompletedEvent() {
        $test_data = $this->constructAttemptCompleted();
        $event = $this->service->read_attempt_completed_event($test_data);
        $this->assertAttemptCompleted($test_data, $event);
    }

    /**
     * Tests the read_user_loggedin_event method of the xapi_service.
     */
    public function testReadUserLoggedinEvent() {
        $test_data = $this->constructUserLoggedin();
        $event = $this->service->read_user_loggedin_event($test_data);
        $this->assertUserLoggedin($test_data, $event);
    }

    /**
     * Tests the read_user_loggedout_event method of the xapi_service.
     */
    public function testReadUserLoggedoutEvent() {
        $test_data = $this->constructUserLoggedout();
        $event = $this->service->read_user_loggedout_event($test_data);
        $this->assertUserLoggedout($test_data, $event);
    }

    /**
     * Tests the read_assignment_graded_event method of the xapi_service.
     */
    public function testReadAssignmentGradedEvent() {
        $test_data = $this->constructAssignmentGraded();
        $event = $this->service->read_assignment_graded_event($test_data);
        $this->assertAssignmentGraded($test_data, $event);
    }

    /**
     * Tests the read_assignment_submitted_event method of the xapi_service.
     */
    public function testReadAssignmentSubmittedEvent() {
        $test_data = $this->constructAssignmentSubmitted();
        $event = $this->service->read_assignment_submitted_event($test_data);
        $this->assertAssignmentSubmitted($test_data, $event);
    }    
}
