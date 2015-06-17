<?php namespace Tests;
use \MXTranslator\Events\AssignmentGraded as Event;

class AssignmentGradedTest extends ModuleViewedTest {
    protected static $recipe_name = 'assignment_graded';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event($this->repo);
    }

    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'grade' => (object) [
                'grade' => 1,
            ],
        ]);
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertEquals($input['grade']->grade, $output['grade_result']);
    }
}
