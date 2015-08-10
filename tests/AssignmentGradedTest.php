<?php namespace Tests;

class AssignmentGradedTest extends TestCase {
    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'objecttable' => 'assign_grades',
            'objectid' => '1',
            'eventname' => '\mod_assign\event\submission_graded',
        ]);
    }
}