<?php namespace Tests\Xapi;
use \Tests\Xapi\BaseTest as TestCase;
use \logstore_emitter\xapi\service as xapi_service;
use \TinCan\RemoteLRS as tincan_remote_lrs;

class ServiceTest extends TestCase {
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->service = new xapi_service(new TestRepository(new tincan_remote_lrs('', '1.0.1', '', '')));
    }

    /**
     * Tests the create method of the xapi_service.
     */
    public function testCreate() {
        $test_data = [
            'user' => (object) [
                'id' => '1',
                'url' => 'http://www.example.com',
                'name' => 'Bob'
            ],
            'object' => (object) [
                'id' => '1',
                'url' => 'http://www.example.com'
            ],
            'course' => (object) [
                'id' => '1',
                'url' => 'http://www.example.com'
            ],
            'eventname' => '\mod_scorm\event\course_module_viewed'
        ];
        $statement = $this->service->create($test_data);

        $this->assertAgent($test_data['user'], $statement->getActor());
        $this->assertActivity($test_data['object'], $statement->getObject());
        $this->assertVerb((object) [
            'id' => 'http://id.tincanapi.com/verb/viewed'
        ], $statement->getVerb());
    }
}
