<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace MXTranslator\Tests;

defined('MOODLE_INTERNAL') || die();

use \MXTranslator\Events\AssignmentGraded as Event;

class assignment_graded_test extends module_viewed_test {
    protected static $recipename = 'assignment_graded';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function construct_input() {
        return array_merge(parent::construct_input(), [
            'grade' => (object) [
                'grade' => 1,
            ],
            'graded_user' => $this->construct_user(),
            'grade_items' => $this->construct_grade_items(),
            'grade_comment' => "test comment"
        ]);
    }

    private function construct_grade_items() {
        return (object) [
            'grademin' => 0,
            'grademax' => 5,
            'gradepass' => 5
        ];
    }

    protected function assert_output($input, $output) {
        parent::assert_output($input, $output);
        $this->assertEquals($input['grade']->grade, $output['grade_score_raw']);
        $this->assertEquals($input['grade_comment'], $output['grade_comment']);
        $this->assertEquals(true, $output['grade_completed']);
        $this->assert_grade_items($input, $output);
        $this->assert_user($input['graded_user'], $output, 'graded_user');
    }

    protected function assert_grade_items($input, $output) {
        $this->assertEquals((float) $input['grade_items']->grademin, $output['grade_score_min']);
        $this->assertEquals((float) $input['grade_items']->grademax, $output['grade_score_max']);
        $this->assertEquals(($input['grade']->grade >= $input['grade_items']->gradepass), $output['grade_success']);
        if ($output['grade_score_scaled'] >= 0) {
            $this->assertEquals($output['grade_score_scaled'], $output['grade_score_raw'] / $output['grade_score_max']);
        } else {
            $this->assertEquals($output['grade_score_scaled'], $output['grade_score_raw'] / $output['grade_score_min']);
        }
    }
}
