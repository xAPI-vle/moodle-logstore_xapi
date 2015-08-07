<?php namespace Tests;

class AssignmentSubmittedTest extends TestCase {
    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'objecttable' => 'assign_submission',
            'objectid' => 1,
            'eventname' => '\mod_assign\event\assessable_submitted',
        ]);
    }
}