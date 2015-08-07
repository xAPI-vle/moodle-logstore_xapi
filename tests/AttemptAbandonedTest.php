<?php namespace Tests;

class AttemptAbandonedTest extends AttemptTestCase {
    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'eventname' => '\mod_quiz\event\attempt_abandoned',
        ]);
    }
}