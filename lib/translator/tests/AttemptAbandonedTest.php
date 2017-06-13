<?php namespace MXTranslator\Tests;
use \MXTranslator\Events\AttemptAbandoned as Event;

class AttemptAbandonedTest extends AttemptReviewedTest {
    protected static $recipeName = 'attempt_abandoned';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function assertAttempt($input, $output) {
        parent::assertAttempt($input, $output);
    }
}