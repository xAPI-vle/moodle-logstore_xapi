<?php namespace Tests\Xapi\Recipes;
use \Tests\Xapi\BaseTest as TestCase;
use \logstore_emitter\xapi\recipes\quiz_submitted as quiz_submitted;

class QuizSubmittedTest extends TestCase {
    /**
     * Tests the __construct method of the quiz_submitted.
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
                'type' => 'course_module'
            ],
            'course' => (object) [
                'id' => '1',
                'url' => 'http://www.example.com',
                'type' => 'course'
            ]
        ];
        $statement = new quiz_submitted($test_data);

        $this->assertAgent($test_data['user'], $statement->getActor());
        $this->assertActivity($test_data['object'], $statement->getObject());
        $this->assertModuleContext($test_data, $statement->getContext());
        $this->assertVerb((object) [
            'id' => 'http://activitystrea.ms/schema/1.0/submit'
        ], $statement->getVerb());
    }
}
