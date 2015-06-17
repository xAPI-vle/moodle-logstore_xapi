<?php namespace Tests;
use \PHPUnit_Framework_TestCase as PhpUnitTestCase;
use \XREmitter\Events\Event as Event;

abstract class EventTest extends PhpUnitTestCase {
    protected static $recipe_name;
    protected $repo;

    public function __construct() {
        $this->repo = new TestRepository(new TestRemoteLrs('', '1.0.1', '', ''));
    }

    /**
     * Sets up the tests.
     * @override PhpUnitTestCase
     */
    public function setup() {}

    /**
     * Tests the read method of the Event.
     */
    public function testRead() {
        $input = $this->constructInput();
        $output = $this->event->read($input);
        $this->assertOutput($input, $output);
    }

    protected function constructInput() {
        return array_merge(
            $this->constructUser(),
            $this->constructLog(),
            ['recipe' => static::$recipe_name]
        );
    }

    private function constructUser() {
        return [
            'user_id' => 1,
            'user_url' => 'http://www.example.com/user_url',
            'user_name' => 'Test user_name',
        ];
    }

    private function constructLog() {
        return [
            'context_lang' => 'en',
            'context_platform' => 'Moodle',
            'context_ext' => [
                'test_context_ext_key' => 'test_context_ext_value',
            ],
            'context_ext_key' => 'http://www.example.com/context_ext_key',
            'time' => '2015-01-01T01:00Z',
        ];
    }

    protected function contructObject($type) {
        return [
            $type.'_url' => 'http://www.example.com/'.$type.'_url',
            $type.'_name' => 'Test '.$type.'_name',
            $type.'_description' => 'Test '.$type.'_description',
            $type.'_ext' => [
                'test_'.$type.'_ext_key' => 'test_'.$type.'_ext_value',
            ],
            $type.'_ext_key' => 'http://www.example.com/'.$type.'_ext_key',
        ];
    }

    protected function constructAttempt() {
        return [
            'attempt_url' => 'http://www.example.com/attempt_url',
            'attempt_ext' => [
                'test_attempt_ext_key' => 'test_attempt_ext_value',
            ],
            'attempt_ext_key' => 'http://www.example.com/attempt_ext_key',
            'attempt_name' => 'Test attempt_name',
        ];
    }

    protected function assertOutput($input, $output) {
        $this->assertUser($input, $output['actor']);
        $this->assertLog($input, $output);
    }

    private function assertUser($input, $output) {
        $this->assertEquals($input['user_id'], $output['account']['name']);
        $this->assertEquals($input['user_url'], $output['account']['homePage']);
        $this->assertEquals($input['user_name'], $output['name']);
    }

    private function assertLog($input, $output) {
        $actual_context = $output['context'];
        $this->assertEquals($input['context_lang'], $actual_context['language']);
        $this->assertEquals($input['context_platform'], $actual_context['platform']);
        $this->assertArrayHasKey($input['context_ext_key'], $actual_context['extensions']);
        $this->assertEquals($input['context_ext'], $actual_context['extensions'][$input['context_ext_key']]);
        $this->assertEquals($input['time'], $output['timestamp']);
    }

    protected function assertObject($type, $input, $output) {
        $this->assertEquals($input[$type.'_url'], $output['id']);
        $this->assertEquals($input[$type.'_name'], $output['definition']['name']['en-GB']);
        $this->assertEquals($input[$type.'_name'], $output['definition']['name']['en-US']);
        $this->assertEquals($input[$type.'_description'], $output['definition']['description']['en-GB']);
        $this->assertEquals($input[$type.'_description'], $output['definition']['description']['en-US']);
        $this->assertArrayHasKey($input[$type.'_ext_key'], $output['definition']['extensions']);
        $this->assertEquals($input[$type.'_ext'], $output['definition']['extensions'][$input[$type.'_ext_key']]);
    }

    protected function assertVerb($verb_id, $verb_name, $output) {
        $this->assertEquals($verb_id, $output['id']);
        $this->assertEquals($verb_name, $output['display']['en-GB']);
        $this->assertEquals($verb_name, $output['display']['en-US']);
    }

    protected function assertAttempt($input, $output) {
        $this->assertEquals($input['attempt_url'], $output['id']);
        $this->assertEquals($input['attempt_name'], $output['definition']['name']['en-GB']);
        $this->assertEquals($input['attempt_name'], $output['definition']['name']['en-US']);
        $this->assertArrayHasKey($input['attempt_ext_key'], $output['definition']['extensions']);
        $this->assertEquals($input['attempt_ext'], $output['definition']['extensions'][$input['attempt_ext_key']]);
    }
}
