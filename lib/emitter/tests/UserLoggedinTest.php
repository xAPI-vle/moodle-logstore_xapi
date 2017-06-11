<?php namespace XREmitter\Tests;
use \XREmitter\Events\UserLoggedin as Event;

class UserLoggedinTest extends EventTest {
    protected static $recipeName = 'user_loggedin';

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
        $this->assertVerb('https://brindlewaye.com/xAPITerms/verbs/loggedin/', 'logged in to', $output['verb']);
        $this->assertObject('app', $input, $output['object']);
    }
}
