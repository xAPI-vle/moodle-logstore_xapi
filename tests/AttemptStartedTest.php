<?php namespace Tests;

class AttemptStartedTest extends AttemptTestCase {
    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'eventname' => '\mod_quiz\event\attempt_preview_started',
        ]);
    }
}
