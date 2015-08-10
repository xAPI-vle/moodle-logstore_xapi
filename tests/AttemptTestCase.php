<?php namespace Tests;

abstract class AttemptTestCase extends TestCase {
    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'objecttable' => 'quiz_attempts',
            'objectid' => 1,
        ]);
    }
}