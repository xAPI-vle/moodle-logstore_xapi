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

use \MXTranslator\Events\AttemptSubmitted as Event;

class attempt_submitted_test extends attempt_started_test {
    protected static $recipename = 'attempt_submitted';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        parent::setup();
        $this->event = new Event();
    }

    protected function construct_input() {
        return array_merge(parent::construct_input(), [
            'grade_items' => $this->construct_grade_items()
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
        $this->assert_attempt($input['attempt'], $output);
        $this->assert_grade_items($input, $output);
    }

    protected function assert_attempt($input, $output) {
        parent::assert_attempt($input, $output);
        $this->assertEquals((float) $input->sumgrades, $output['attempt_score_raw']);
    }

    protected function assert_grade_items($input, $output) {
        $this->assertEquals((float) $input['grade_items']->grademin, $output['attempt_score_min']);
        $this->assertEquals((float) $input['grade_items']->grademax, $output['attempt_score_max']);
        $this->assertEquals(($input['attempt']->sumgrades >= $input['grade_items']->gradepass), $output['attempt_success']);
        if ($output['attempt_score_scaled'] >= 0) {
            $this->assertEquals($output['attempt_score_scaled'], $output['attempt_score_raw'] / $output['attempt_score_max']);
        } else {
            $this->assertEquals($output['attempt_score_scaled'], $output['attempt_score_raw'] / $output['attempt_score_min']);
        }
    }
}
