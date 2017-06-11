<?php namespace XREmitter\Tests;
use \XREmitter\Events\QuestionAnswered as Event;

class QuestionAnsweredTest extends EventTest {
    protected static $recipeName = 'attempt_question_completed';

    /**
     * Sets up the tests.
     * @override EventTest
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function constructInput() {
        return array_merge(
            parent::constructInput(),
            $this->contructObject('course'),
            $this->contructObject('module'),
            $this->constructQuestion(),
            $this->constructAttempt()
        );
    }

    protected function constructAttempt() {
        return array_merge(parent::constructAttempt(), [
            'attempt_score_raw' => 1,
            'attempt_score_min' => 0,
            'attempt_score_max' => 5,
            'attempt_score_scaled' => 0.2,
            'attempt_success' => false,
            'attempt_completed' => true,
            'attempt_response' => 'test response',
        ]);
    }

    protected function constructQuestion() {
        return array_merge(
            parent::contructObject('question', 'http://adlnet.gov/expapi/activities/cmi.interaction'),
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

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertVerb('http://adlnet.gov/expapi/verbs/answered', 'answered', $output['verb']);
        $this->assertEquals($input['attempt_url'], $output['context']['contextActivities']['grouping'][2]['id']);
        $this->assertObject('course', $input, $output['context']['contextActivities']['grouping'][1]);
        $this->assertObject('module', $input, $output['context']['contextActivities']['parent'][0]);
        $this->assertObject('question', $input, $output['object']);
        $this->assertEquals($input['interaction_type'], $output['object']['definition']['interactionType']);
        $this->assertEquals($input['interaction_correct_responses'], $output['object']['definition']['correctResponsesPattern']);
        $this->assertComponentList($input['interaction_choices'], $output['object']['definition']['choices'], $input['context_lang']);
        $this->assertEquals($input['attempt_score_raw'], $output['result']['score']['raw']);
        $this->assertEquals($input['attempt_score_min'], $output['result']['score']['min']);
        $this->assertEquals($input['attempt_score_max'], $output['result']['score']['max']);
        $this->assertEquals($input['attempt_score_scaled'], $output['result']['score']['scaled']);
        $this->assertEquals($input['attempt_success'], $output['result']['success']);
        $this->assertEquals($input['attempt_completed'], $output['result']['completion']);
        $this->assertEquals($input['attempt_response'], $output['result']['response']);
    }
}
