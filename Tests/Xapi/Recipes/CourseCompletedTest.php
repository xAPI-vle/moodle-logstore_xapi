<?php namespace Tests\Xapi\Recipes;
use \Tests\Xapi\BaseTest as TestCase;
use \logstore_emitter\xapi\recipes\course_completed as course_completed;

class CourseCompletedTest extends TestCase {
    /**
     * Tests the __construct method of the course_completed.
     */
    public function testConstruct() {
        $test_data = [
            'user' => (object) [
                'id' => '1',
                'url' => 'http://www.example.com',
                'name' => 'Bob'
            ],
            'object' => (object) [
                'id' => '1',
                'url' => 'http://www.example.com'
            ]
        ];
        $statement = new course_completed($test_data);
        
        $this->assertAgent($test_data['user'], $statement->getActor());
        $this->assertActivity($test_data['object'], $statement->getObject());
        $this->assertVerb((object) [
            'id' => 'http://activitystrea.ms/schema/1.0/complete'
        ], $statement->getVerb());
    }
}
