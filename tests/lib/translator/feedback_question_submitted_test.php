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

use \MXTranslator\Events\FeedbackQuestionSubmitted as Event;

class feedback_question_submitted_test extends feedback_submitted_test {
    protected static $recipename = 'attempt_question_completed';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        parent::setup();
        $this->event = new Event();
    }

    protected function assert_output($input, $output) {
        parent::assert_output($input, $output);

        $questions = $input['questions'];
        $questionsarr = (array) $questions;

        $responses = $input['attempt']->responses;
        $responsesarr = (array) $responses;

        $this->assertEquals($questionsarr['1']->name, $output['question_name']);
        $this->assertEquals($questionsarr['1']->name, $output['question_description']);
        $this->assertEquals($questionsarr['1']->url, $output['question_url']);
        $this->assertEquals($responsesarr['2']->value, $output['attempt_response']);
        $this->assertEquals(null, $output['interaction_correct_responses']);
        $this->assertEquals('likert', $output['interaction_type']);
        $this->assertEquals((object) [
            "0" => "Not selected",
            '1' => "incorrect",
            '2' => "correct"
        ], $output['interaction_scale']);
    }

    protected function assert_attempt($input, $output) {
        // Overides parent and does nothing.
    }

}
