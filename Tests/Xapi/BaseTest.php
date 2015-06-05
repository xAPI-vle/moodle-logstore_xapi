<?php namespace Tests\Xapi;
use \Tests\BaseTest as TestCase;
use \logstore_emitter\xapi\agent as xapi_agent;
use \stdClass as php_obj;

abstract class BaseTest extends TestCase {
    /**
     * Asserts that the data is a correctly formed agent from the given test data.
     * @param php_obj $test_data
     * @param $data
     */
    protected function assertAgent(php_obj $test_data, $data) {
        $this->assertInstanceOf('logstore_emitter\xapi\agent', $data);
        $this->assertEquals('Agent', $data->getObjectType());
        $this->assertEquals($test_data->name, $data->getName());
        $this->assertAccount($test_data, $data);
    }

    /**
     * Asserts that the agent's account is correct according to the given test data.
     * @param php_obj $test_data
     * @param xapi_agent $agent
     */
    protected function assertAccount(php_obj $test_data, xapi_agent $agent) {
        $account = $agent->getAccount();
        $this->assertInstanceOf('TinCan\AgentAccount', $account);
        $this->assertEquals($test_data->url, $account->getHomePage());
        $this->assertEquals($test_data->id, $account->getName());
    }

    /**
     * Asserts that the data is a correctly formed activity from the given test data.
     * @param php_obj $test_data
     * @param $data
     */
    protected function assertActivity(php_obj $test_data, $data) {
        $this->assertInstanceOf('logstore_emitter\xapi\activity', $data);
        $this->assertEquals($test_data->url, $data->getId());
        $this->assertEquals(null, $data->getDefinition());
    }

    /**
     * Asserts that the data is a correctly formed verb from the given test data.
     * @param php_obj $test_data
     * @param $data
     */
    protected function assertVerb(php_obj $test_data, $data) {
        $this->assertInstanceOf('TinCan\Verb', $data);
        $this->assertEquals($test_data->id, $data->getId());
    }
}
