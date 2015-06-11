<?php namespace Tests\Xapi;
use \Tests\BaseTest as TestCase;
use \logstore_emitter\xapi\repository as xapi_repository;

class RepositoryTest extends TestCase {
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->repo = new xapi_repository(new TestRemoteLrs('', '1.0.1', '', ''));
    }

    /**
     * Tests the create_event method of the xapi_repository.
     */
    public function testCreateEvent() {
        $test_data = [
            'actor' => [
                'name' => 'Bob',
                'account' => [
                    'name' => '1',
                    'homePage' => 'http://www.example.com'
                ]
            ],
            'verb' => [
                'id' => 'http://id.tincanapi.com/verb/viewed',
                'display' => [
                    'en-GB' => 'viewed',
                    'en-US' => 'viewed',
                ]
            ],
            'object' => [
                'id' => 'http://www.example.com'
            ]
        ];
        $event = $this->repo->create_event($test_data);

        $this->assertEquals($test_data, $event);
    }
}
