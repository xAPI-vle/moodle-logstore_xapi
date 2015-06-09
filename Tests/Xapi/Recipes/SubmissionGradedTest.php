<?php namespace Tests\Xapi\Recipes;
use \Tests\Xapi\BaseTest as TestCase;
use \logstore_emitter\xapi\recipes\submission_graded as submission_graded;

class SubmissionGradedTest extends TestCase {
    /**
     * Tests the __construct method of the submission_graded.
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
                'type' => 'course_module',
                'name' => 'Test Course'
            ],
            'course' => (object) [
                'id' => '1',
                'url' => 'http://www.example.com',
                'type' => 'course',
                'name' => 'Test Course'
            ]
        ];
        $statement = new submission_graded($test_data);

        $this->assertAgent($test_data['user'], $statement->getActor());
        $this->assertActivity($test_data['object'], $statement->getObject());
        $this->assertModuleContext($test_data, $statement->getContext());
        $this->assertVerb((object) [
            'id' => 'http://activitystrea.ms/schema/1.0/submit'
        ], $statement->getVerb());
    }
}
