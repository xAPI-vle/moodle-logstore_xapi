<?php namespace Tests\Xapi;
use \logstore_emitter\xapi\service as xapi_service;
use \logstore_emitter\xapi\controller as xapi_controller;

class ControllerTest extends BaseTest {
    /**
     * Sets up the tests.
     * @override BaseTest
     */
    public function setup() {
        $this->controller = new xapi_controller(new xapi_service(new TestRepository(new TestRemoteLrs('', '1.0.1', '', ''))));
    }

    /**
     * Tests the create_event method of the xapi_controller.
     */
    public function testCreateEvent() {
        $test_data = [];
        $event = $this->controller->create_event($test_data);
        $this->assertEquals(null, $event);
    }

    /**
     * Tests the create_event method of the xapi_controller with a course_viewed event.
     */
    public function testCreateCourseViewedEvent() {
        $test_data = $this->constructCourseViewed();
        $event = $this->controller->create_event($test_data);
        $this->assertCourseViewed($test_data, $event);
    }

    /**
     * Tests the create_event method of the xapi_controller with a module_viewed event.
     */
    public function testCreateModuleViewedEvent() {
        $test_data = $this->constructModuleViewed();
        $event = $this->controller->create_event($test_data);
        $this->assertModuleViewed($test_data, $event);
    }

    /**
     * Tests the create_event method of the xapi_controller with a attempt_started event.
     */
    public function testCreateAttemptStartedEvent() {
        $test_data = $this->constructAttemptStarted();
        $event = $this->controller->create_event($test_data);
        $this->assertAttemptStarted($test_data, $event);
    }

    /**
     * Tests the create_event method of the xapi_controller with a attempt_completed event.
     */
    public function testCreateAttemptCompletedEvent() {
        $test_data = $this->constructAttemptCompleted();
        $event = $this->controller->create_event($test_data);
        $this->assertAttemptCompleted($test_data, $event);
    }

    /**
     * Tests the create_event method of the xapi_controller with a user_loggedin event.
     */
    public function testCreateUserLoggedinEvent() {
        $test_data = $this->constructUserLoggedin();
        $event = $this->controller->create_event($test_data);
        $this->assertUserLoggedin($test_data, $event);
    }

    /**
     * Tests the create_event method of the xapi_controller with a user_loggedout event.
     */
    public function testCreateUserLoggedoutEvent() {
        $test_data = $this->constructUserLoggedout();
        $event = $this->controller->create_event($test_data);
        $this->assertUserLoggedout($test_data, $event);
    }

    /**
     * Tests the create_event method of the xapi_controller with a assignment_graded event.
     */
    public function testCreateAssignmentGradedEvent() {
        $test_data = $this->constructAssignmentGraded();
        $event = $this->controller->create_event($test_data);
        $this->assertAssignmentGraded($test_data, $event);
    }

    /**
     * Tests the create_event method of the xapi_controller with a assignment_submitted event.
     */
    public function testCreateAssignmentSubmittedEvent() {
        $test_data = $this->constructAssignmentSubmitted();
        $event = $this->controller->create_event($test_data);
        $this->assertAssignmentSubmitted($test_data, $event);
    }
}