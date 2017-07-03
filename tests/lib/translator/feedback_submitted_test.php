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

use \MXTranslator\Events\FeedbackSubmitted as Event;

class feedback_submitted_test extends module_viewed_test {
    protected static $recipename = 'attempt_completed';

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
            'attempt' => $this->construct_attempt(),
            'questions' => $this->construct_questions()
        ]);
    }

    private function construct_attempt() {
        return (object) [
            'url' => 'http://www.example.com/attempt_url',
            'name' => 'Test attempt_name',
            'type' => 'moodle_feedback_attempt',
            'timemodified' => 1433946701,
            'responses' => (object) [
                "2" => (object) [
                    "id" => "2",
                    "item" => "1",
                    "value" => "2"
                ]
            ]
        ];
    }

    private function construct_questions() {
        return (object) [
            "1" => (object) [
                "id" => "1",
                "feedback" => "1",
                "name" => "example MCQ",
                "label" => "",
                "presentation" => "r>>>>>0#### incorrect|1#### correct",
                "typ" => "multichoicerated",
                "hasvalue" => "1",
                "position" => "1",
                "required" => "0",
                "dependitem" => "0",
                "dependvalue" => "",
                "options" => "",
                "template" => false,
                "url" => "http://www.example.com/question_url"
            ]
        ];
    }

    protected function assert_output($input, $output) {
        parent::assert_output($input, $output);
        $this->assert_attempt($input['attempt'], $output);
        $this->assertEquals(0, $output['attempt_score_min']);
        $this->assertEquals(1, $output['attempt_score_max']);
        $this->assertEquals(1, $output['attempt_score_scaled']);
        $this->assertEquals(null, $output['attempt_success']);
        $this->assertEquals(true, $output['attempt_completed']);
        $this->assertEquals(null, $output['attempt_duration']);
    }

    protected function assert_attempt($input, $output) {
        $extkey = 'http://lrs.learninglocker.net/define/extensions/moodle_feedback_attempt';
        $this->assertEquals($input->url, $output['attempt_url']);
        $this->assertEquals($input->name, $output['attempt_name']);
        $this->assertEquals(static::$xapitype.$input->type, $output['attempt_type']);
        $this->assertEquals($input, $output['attempt_ext']);
        $this->assertEquals($extkey, $output['attempt_ext_key']);
    }

}

