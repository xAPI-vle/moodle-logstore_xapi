<?php namespace Tests;

class EnrolmentCreatedTest extends TestCase {
    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'eventname' => '\core\event\user_enrolment_created',
        ]);
    }
}
