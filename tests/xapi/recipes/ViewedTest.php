<?php namespace Tests\Xapi\Recipes;
use \Tests\XapiBaseTest as TestCase;
use \logstore_emitter\xapi\recipes\viewed as viewed_recipe;

class ViewedTest extends TestCase {
    /**
     * Tests the __construct method of the viewed_recipe.
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
        $statement = new viewed_recipe($test_data);
        
        $this->assertAgent($test_data['user'], $statement->getActor());
        $this->assertActivity($test_data['object'], $statement->getObject());
        $this->assertVerb((object) [
            'id' => 'http://id.tincanapi.com/verb/viewed'
        ], $statement->getVerb());
    }
}
