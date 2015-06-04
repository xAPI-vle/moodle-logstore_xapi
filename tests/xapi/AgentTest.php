<?php namespace Tests\Xapi;
use \Tests\Xapi\BaseTest as TestCase;
use \logstore_emitter\xapi\agent as xapi_agent;

class AgentTest extends TestCase {
    /**
     * Tests the __construct method of the xapi_agent.
     */
    public function testConstruct() {
        $test_data = (object) [
            'id' => '1',
            'url' => 'http://www.example.com',
            'name' => 'Bob'
        ];
        $data = new xapi_agent($test_data);
        $this->assertAgent($test_data, $data);
    }
}
