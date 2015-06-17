<?php namespace Tests;
use \MXTranslator\Events\AssignmentSubmitted as Event;

class AssignmentSubmittedTest extends ModuleViewedTest {
    protected static $recipe_name = 'assignment_submitted';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event($this->repo);
    }

    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'submission' => (object) [],
        ]);
    }
}
