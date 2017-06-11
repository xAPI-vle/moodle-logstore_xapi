<?php namespace MXTranslator\Tests;
use \MXTranslator\Events\FeedbackSubmitted as Event;

class FeedbackSubmittedTest extends ModuleViewedTest {
    protected static $recipe_name = 'attempt_completed';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'attempt' => $this->constructAttempt(),
            'questions' => $this->constructQuestions()
        ]);
    }

    private function constructAttempt() {
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

    private function constructQuestions() {
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

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertAttempt($input['attempt'], $output);
        $this->assertEquals(0, $output['attempt_score_min']);
        $this->assertEquals(1, $output['attempt_score_max']);
        $this->assertEquals(1, $output['attempt_score_scaled']);
        $this->assertEquals(null, $output['attempt_success']);
        $this->assertEquals(true, $output['attempt_completed']);
        $this->assertEquals(null, $output['attempt_duration']);
    }

    protected function assertAttempt($input, $output) {
        $ext_key = 'http://lrs.learninglocker.net/define/extensions/moodle_feedback_attempt';
        $this->assertEquals($input->url, $output['attempt_url']);
        $this->assertEquals($input->name, $output['attempt_name']);
        $this->assertEquals(static::$xapiType.$input->type, $output['attempt_type']);
        $this->assertEquals($input, $output['attempt_ext']);
        $this->assertEquals($ext_key, $output['attempt_ext_key']);
    }

}

