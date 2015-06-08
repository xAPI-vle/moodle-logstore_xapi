<?php namespace Tests\Moodle;
use \Tests\BaseTest as TestCase;
use \logstore_emitter\moodle\service as moodle_service;
use \stdClass as php_obj;

class TestService extends TestCase {
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->service = new moodle_service(new TestRepository((object) [], (object) []));
    }

    /**
     * Tests the create method of the xapi_service.
     */
    public function testCreate() {
        $test_data = [
            'userid' => '1',
            'courseid' => '1'
        ];
        $opts = $this->service->create($test_data);

        $this->assertUser($test_data['userid'], $opts['user']);
        $this->assertObject($test_data, $opts['object']);
    }

    /**
     * Asserts that the data is a correctly formed user from the given test data.
     * @param String $user_id
     * @param $data
     */
    private function assertUser($user_id, $data) {
        $this->assertInstanceOf('stdClass', $data);

        // Asserts correct attributes.
        $this->assertObjectHasAttribute('id', $data);
        $this->assertObjectHasAttribute('name', $data);
        $this->assertObjectHasAttribute('url', $data);

        // Asserts correct attribute types.
        $this->assertInternalType('string', $data->id);
        $this->assertInternalType('string', $data->url);
        $this->assertInternalType('string', $data->name);

        // Asserts correct attribute values.
        $this->assertEquals($user_id, $data->id);
    }

    /**
     * Asserts that the data is a correctly formed object from the given test data.
     * @param array $test_data
     * @param $data
     */
    private function assertObject(array $test_data, $data) {
        $this->assertInstanceOf('stdClass', $data);

        // Asserts correct attributes.
        $this->assertObjectHasAttribute('id', $data);
        $this->assertObjectHasAttribute('url', $data);

        // Asserts correct attribute types.
        $this->assertInternalType('string', $data->id);
        $this->assertInternalType('string', $data->url);
    }
}
