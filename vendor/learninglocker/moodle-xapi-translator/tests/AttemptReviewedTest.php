<?php namespace Tests;
use \MXTranslator\Events\AttemptReviewed as Event;

class AttemptReviewedTest extends AttemptStartedTest {
    protected static $recipe_name = 'attempt_completed';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event($this->repo);
    }

    protected function assertAttempt($input, $output) {
        parent::assertAttempt($input, $output);
        $this->assertEquals((float) $input->sumgrades, $output['attempt_result']);
        $this->assertEquals($input->state === 'finished', $output['attempt_completed']);
    }
}
