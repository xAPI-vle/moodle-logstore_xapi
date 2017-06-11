<?php namespace MXTranslator\Tests;
use \MXTranslator\Events\AssignmentGraded as Event;

class AssignmentGradedTest extends ModuleViewedTest {
    protected static $recipeName = 'assignment_graded';

    /**
 * Sets up the tests.
 * @override TestCase
 */
    public function setup() {
        $this->event = new Event();
    }

    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'grade' => (object) [
                'grade' => 1,
            ],
            'graded_user' => $this->constructUser(),
            'grade_items' => $this->constructGradeitems(),
            'grade_comment' => "test comment"
        ]);
    }

    private function constructGradeitems() {
        return (object) [
            'grademin' => 0,
            'grademax' => 5,
            'gradepass' => 5
        ];
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertEquals($input['grade']->grade, $output['grade_score_raw']);
        $this->assertEquals($input['grade_comment'], $output['grade_comment']);
        $this->assertEquals(true, $output['grade_completed']);
        $this->assertGradeItems($input, $output);
        $this->assertUser($input['graded_user'], $output, 'graded_user');
    }

    protected function assertGradeItems($input, $output) {
        $this->assertEquals((float) $input['grade_items']->grademin, $output['grade_score_min']);
        $this->assertEquals((float) $input['grade_items']->grademax, $output['grade_score_max']);
        $this->assertEquals(($input['grade']->grade >= $input['grade_items']->gradepass), $output['grade_success']);
        if ($output['grade_score_scaled']  >= 0) {
            $this->assertEquals($output['grade_score_scaled'], $output['grade_score_raw'] / $output['grade_score_max']);
        }
        else
        {
            $this->assertEquals($output['grade_score_scaled'], $output['grade_score_raw'] / $output['grade_score_min']);
        }
    }
}
