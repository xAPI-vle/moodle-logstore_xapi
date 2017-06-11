<?php namespace MXTranslator\Tests;
use \MXTranslator\Events\UserLoggedin as Event;

class UserLoggedinTest extends UserEventTest {
    protected static $recipe_name = 'user_loggedin';
    
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event();
    }
}
