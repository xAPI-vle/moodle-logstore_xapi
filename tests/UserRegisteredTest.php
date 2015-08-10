<?php namespace Tests;

class UserRegisteredTest extends TestCase {
    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'eventname' => '\core\event\user_created',
        ]);
    }
}