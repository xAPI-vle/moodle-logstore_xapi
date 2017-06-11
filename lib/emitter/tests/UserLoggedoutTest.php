<?php namespace XREmitter\Tests;
use \XREmitter\Events\UserLoggedout as Event;

class UserLoggedoutTest extends EventTest {
    protected static $recipe_name = 'user_loggedout';

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
        $this->assertVerb('https://brindlewaye.com/xAPITerms/verbs/loggedout/', 'logged out of', $output['verb']);
        $this->assertObject('app', $input, $output['object']);
    }
}
