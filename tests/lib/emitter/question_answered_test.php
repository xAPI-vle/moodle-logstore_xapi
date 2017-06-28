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

use \XREmitter\Events\QuestionAnswered as Event;

class question_answered_test extends event_test {
    protected static $recipename = 'attempt_question_completed';

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
            $this->construct_question(),
            $this->construct_attempt()
        );
    }

    protected function construct_attempt() {
        return array_merge(parent::construct_attempt(), [
            'attempt_score_raw' => 1,
            'attempt_score_min' => 0,
            'attempt_score_max' => 5,
            'attempt_score_scaled' => 0.2,
            'attempt_success' => false,
            'attempt_completed' => true,
            'attempt_response' => 'test response',
        ]);
    }

    protected function construct_question() {
        return array_merge(
            parent::construct_object('question', 'http://adlnet.gov/expapi/activities/cmi.interaction'),
            [
                'interaction_type' => 'choice',
                'interaction_correct_responses' => ['[9,10]'],
                'interaction_choices' => [
                    '8' => 'test incorrect choice',
                    '9' => 'test correct choice 1',
                    '10' => 'test correct choice 2'
                ]
            ]
        );
    }

    protected function assert_output($input, $output) {
        parent::assert_output($input, $output);
        $this->assert_verb('http://adlnet.gov/expapi/verbs/answered', 'answered', $output['verb']);
        $this->assertEquals($input['attempt_url'], $output['context']['contextActivities']['grouping'][2]['id']);
        $this->assert_object('course', $input, $output['context']['contextActivities']['grouping'][1]);
        $this->assert_object('module', $input, $output['context']['contextActivities']['parent'][0]);
        $this->assert_object('question', $input, $output['object']);
        $this->assertEquals($input['interaction_type'], $output['object']['definition']['interactionType']);
        $this->assertEquals($input['interaction_correct_responses'], $output['object']['definition']['correctResponsesPattern']);
        $this->assert_component_list(
            $input['interaction_choices'],
            $output['object']['definition']['choices'], $input['context_lang']
        );
        $this->assertEquals($input['attempt_score_raw'], $output['result']['score']['raw']);
        $this->assertEquals($input['attempt_score_min'], $output['result']['score']['min']);
        $this->assertEquals($input['attempt_score_max'], $output['result']['score']['max']);
        $this->assertEquals($input['attempt_score_scaled'], $output['result']['score']['scaled']);
        $this->assertEquals($input['attempt_success'], $output['result']['success']);
        $this->assertEquals($input['attempt_completed'], $output['result']['completion']);
        $this->assertEquals($input['attempt_response'], $output['result']['response']);
    }
}
