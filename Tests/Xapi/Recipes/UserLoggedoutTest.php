<?php namespace Tests\Xapi\Recipes;
use \Tests\Xapi\BaseTest as TestCase;
use \logstore_emitter\xapi\recipes\user_loggedout as user_loggedout;

class UserLoggedoutTest extends TestCase {
    /**
     * Tests the __construct method of the user_loggedout.
     */
    public function testConstruct() {
        $test_data = [
            'user' => (object) [
                'id' => '1',
                'url' => 'http://www.example.com',
                'name' => 'Bob',
                'type' => 'user'
            ],
            'object' => (object) [
                'url' => 'http://www.example.com'
            ]
        ];
        $statement = new user_loggedout($test_data);

        $this->assertAgent($test_data['user'], $statement->getActor());
        $this->assertActivity($test_data['object'], $statement->getObject());
        $this->assertVerb((object) [
            'id' => 'https://brindlewaye.com/xAPITerms/verbs/loggedout/'
        ], $statement->getVerb());
    }
}
