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

namespace XREmitter\Tests;

defined('MOODLE_INTERNAL') || die();

use \XREmitter\Events\AssignmentGraded as Event;

class assignment_graded_test extends event_test {
    protected static $recipename = 'assignment_graded';

    /**
     * Sets up the tests.
     * @override EventTest
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function construct_input() {
        return array_merge(
            parent::construct_input(),
            $this->construct_object('course'),
            $this->construct_object('module'),
            $this->construct_user('graded_user'),
            [
                'grade_score_raw' => 47,
                'grade_score_min' => 0,
                'grade_score_max' => 100,
                'grade_score_scaled' => 0.47,
                'grade_success' => true,
                'grade_completed' => true,
                'grade_comment' => 'test comment from instructor'
            ]
        );
    }

    protected function assert_output($input, $output) {
        $this->assert_user($input, $output['actor'], 'graded_user');
        $this->assert_object('app', $input, $output['context']['contextActivities']['grouping'][0]);
        $this->assert_object('source', $input, $output['context']['contextActivities']['category'][0]);
        $this->assert_log($input, $output);
        $this->assert_info(
            $input['context_info'],
            $output['context']['extensions']['http://lrs.learninglocker.net/define/extensions/info']
        );
        $this->assert_valid_xapi_statement($output);
        $this->assert_verb('http://adlnet.gov/expapi/verbs/scored', 'received grade for', $output['verb']);
        $this->assert_object('module', $input, $output['object']);
        $this->assert_object('course', $input, $output['context']['contextActivities']['parent'][0]);
        $this->assertEquals($input['grade_score_raw'], $output['result']['score']['raw']);
        $this->assertEquals($input['grade_score_min'], $output['result']['score']['min']);
        $this->assertEquals($input['grade_score_max'], $output['result']['score']['max']);
        $this->assertEquals($input['grade_score_scaled'], $output['result']['score']['scaled']);
        $this->assertEquals($input['grade_success'], $output['result']['success']);
        $this->assertEquals($input['grade_completed'], $output['result']['completion']);
        $this->assertEquals($input['grade_comment'], $output['result']['response']);
        $this->assert_user($input, $output['context']['instructor'], 'user');
    }
}
