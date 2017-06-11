<?php namespace XREmitter\Tests;
use \XREmitter\Events\UserRegistered as Event;

class UserRegisteredTest extends EventTest {
    protected static $recipe_name = 'user_registered';

    /**
     * Sets up the tests.
     * @override EventTest
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function constructInput() {
        return array_merge(
            parent::constructInput(),
            $this->constructApp()
        );
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertVerb('http://adlnet.gov/expapi/verbs/registered', 'registered to', $output['verb']);
        $this->assertObject('app', $input, $output['object']);
    }
}
