<?php namespace Tests\Xapi;
use \Tests\Xapi\BaseTest as TestCase;
use \logstore_emitter\xapi\repository as xapi_repository;
use \logstore_emitter\xapi\recipes\base as base_recipe;

class RepositoryTest extends TestCase {
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->repo = new xapi_repository(new TestRemoteLrs('', '1.0.1', '', ''));
    }

    /**
     * Tests the saveStatement method of the xapi_repository.
     */
    public function testSaveStatement() {
        $test_data = new base_recipe([
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
        ]);
        $statement = $this->repo->create_statement($test_data);

        $this->assertEquals($test_data, $statement);
    }
}
