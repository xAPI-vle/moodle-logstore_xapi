<?php namespace Tests\Xapi;
use \Tests\Xapi\BaseTest as TestCase;
use \logstore_emitter\xapi\activity as xapi_activity;

class ActivityTest extends TestCase {
    /**
     * Tests the __construct method of the xapi_activity.
     */
    public function testConstruct() {
        $test_data = (object) [
            'id' => '1',
            'url' => 'http://www.example.com'
        ];
        $data = new xapi_activity($test_data);
        $this->assertActivity($test_data, $data);
    }
}
