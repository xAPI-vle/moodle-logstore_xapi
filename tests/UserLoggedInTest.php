<?php namespace Tests;

class UserLoggedInTest extends TestCase {
    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'eventname' => '\core\event\user_loggedin',
        ]);
    }
}