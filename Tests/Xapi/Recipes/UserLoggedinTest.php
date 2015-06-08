<?php namespace Tests\Xapi\Recipes;
use \Tests\Xapi\BaseTest as TestCase;
use \logstore_emitter\xapi\recipes\user_loggedin as user_loggedin;

class UserLoggedinTest extends TestCase {
    /**
     * Tests the __construct method of the user_loggedin.
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
                'id' => '1',
                'url' => 'http://www.example.com',
                'type' => 'course'
            ]
        ];
        $statement = new user_loggedin($test_data);

        $this->assertAgent($test_data['user'], $statement->getActor());
        $this->assertActivity($test_data['object'], $statement->getObject());
        $this->assertVerb((object) [
            'id' => 'https://brindlewaye.com/xAPITerms/verbs/loggedin/'
        ], $statement->getVerb());
    }
}
