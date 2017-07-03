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

use \MXTranslator\Events\QuestionSubmitted as Event;

class question_submitted_test extends attempt_started_test {
    protected static $recipename = 'attempt_question_completed';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        parent::setup();
        $this->event = new Event();
    }

    protected function construct_input() {
        $input = array_merge(parent::construct_input(), [
            'questions' => $this->construct_questions()
        ]);
        $input['attempt']->questions = $this->construct_question_attempts();

        return $input;
    }

    private function construct_question_attempts() {
        return [
            $this->construct_question_attempt(0, 'truefalse'),
            $this->construct_question_attempt(1, 'multichoice'),
            $this->construct_question_attempt(2, 'calculated'),
            $this->construct_question_attempt(3, 'calculatedmulti'),
            $this->construct_question_attempt(4, 'calculatedsimple'),
            $this->construct_question_attempt(5, 'randomsamatch'),
            $this->construct_question_attempt(6, 'match'),
            $this->construct_question_attempt(7, 'shortanswer'),
            $this->construct_question_attempt(8, 'somecustomquestiontypethatsnotstandardinmoodle'),
            $this->construct_question_attempt(9, 'someothertypewithnoanswers'),
            $this->construct_question_attempt(10, 'shortanswer'),
            $this->construct_question_attempt(11, 'numerical')
        ];
    }

    private function construct_question_attempt($index, $qtype) {
        $questionattempt = (object) [
            'id' => $index,
            'questionid' => $index,
            'maxmark' => '5.0000000',
            'steps' => [
                (object)[
                    'sequencenumber' => 1,
                    'state' => 'todo',
                    'timecreated' => '1433946000',
                    'fraction' => null
                ],
                (object)[
                    'sequencenumber' => 2,
                    'state' => 'gradedright',
                    'timecreated' => '1433946701',
                    'fraction' => '1.0000000'
                ],
            ],
            'responsesummary' => 'test answer',
            'rightanswer' => 'test answer',
            'variant' => '1'
        ];

        $choicetypes = [
            'multichoice',
            'somecustomquestiontypethatsnotstandardinmoodle'
        ];

        $matchtypes = [
            'randomsamatch',
            'match'
        ];

        $numerictypes = [
            'numerical',
            'calculated',
            'calculatedmulti',
            'calculatedsimple'
        ];

        if (in_array($qtype, $matchtypes)) {
            $questionattempt->responsesummary = 'test question -> test answer; test question 2 -> test answer 4';
            $questionattempt->rightanswer = 'test question -> test answer; test question 2 -> test answer 4';
        } else if (in_array($qtype, $choicetypes)) {
            $questionattempt->responsesummary = 'test answer; test answer 2';
            $questionattempt->rightanswer = 'test answer; test answer 2';
        } else if (in_array($qtype, $numerictypes)) {
            $questionattempt->responsesummary = '5';
            $questionattempt->rightanswer = '5';
        } else if ($qtype == 'truefalse') {
            $questionattempt->responsesummary = 'True';
            $questionattempt->rightanswer = 'True';
        }

        return $questionattempt;
    }

    private function construct_questions() {
        return [
            $this->construct_question('00', 'truefalse'),
            $this->construct_question('01', 'multichoice'),
            $this->construct_question('02', 'calculated'),
            $this->construct_question('03', 'calculatedmulti'),
            $this->construct_question('04', 'calculatedsimple'),
            $this->construct_question('05', 'randomsamatch'),
            $this->construct_question('06', 'match'),
            $this->construct_question('07', 'shortanswer'),
            $this->construct_question('08', 'somecustomquestiontypethatsnotstandardinmoodle'),
            $this->construct_question('09', 'someothertypewithnoanswers'),
            $this->construct_question('10', 'shortanswer'),
            $this->construct_question('11', 'numerical')
        ];
    }

    private function construct_question($index, $qtype) {
        $question = (object) [
            'id' => $index,
            'name' => 'test question '.$index,
            'questiontext' => 'test question',
            'url' => 'http://localhost/moodle/question/question.php?id='.$index,
            'answers' => [
                '1' => (object)[
                    'id' => '1',
                    'answer' => 'test answer',
                    'fraction' => '0.50'
                ],
                '2' => (object)[
                    'id' => '2',
                    'answer' => 'test answer 2',
                    'fraction' => '0.50'
                ],
                '3' => (object)[
                    'id' => '3',
                    'answer' => 'wrong test answer',
                    'fraction' => '0.00'
                ]
            ],
            'qtype' => $qtype
        ];

        if ($question->qtype == 'numerical') {
            $question->numerical = (object)[
                'answers' => [
                    '1' => (object)[
                        'id' => '1',
                        'question' => $index,
                        'answer' => '1',
                        'tolerance' => '1'
                    ],
                    '2' => (object)[
                        'id' => '2',
                        'question' => $index,
                        'answer' => '2',
                        'tolerance' => '1'
                    ]
                ]
            ];
            $question->answers = [
                '1' => (object)[
                    'id' => '1',
                    'answer' => '5',
                    'fraction' => '1.00'
                ],
                '2' => (object)[
                    'id' => '2',
                    'answer' => '10',
                    'fraction' => '0.00'
                ]
            ];
        } else if ($question->qtype == 'match') {
            $question->match = (object)[
                'subquestions' => [
                    '1' => (object)[
                        'id' => '1',
                        'questiontext' => '<p>test question</p>',
                        'answertext' => '<p>test answer</p>'
                    ],
                    '2' => (object)[
                        'id' => '4',
                        'questiontext' => '<p>test question 2</p>',
                        'answertext' => '<p>test answer 4</p>'
                    ]
                ]
            ];
        } else if (strpos($question->qtype, 'calculated') === 0) {
            $question->calculated = (object)[
                'answers' => [
                    '1' => (object)[
                        'id' => '1',
                        'question' => $index,
                        'answer' => '1',
                        'tolerance' => '1'
                    ],
                    '2' => (object)[
                        'id' => '2',
                        'question' => $index,
                        'answer' => '2',
                        'tolerance' => '1'
                    ]
                ]
            ];
            $question->answers = [
                '1' => (object)[
                    'id' => '1',
                    'answer' => '5',
                    'fraction' => '1.00'
                ],
                '2' => (object)[
                    'id' => '2',
                    'answer' => '10',
                    'fraction' => '0.00'
                ]
            ];
        } else if ($question->qtype == 'shortanswer') {
            $question->shortanswer = (object)[
                'options' => (object)[
                    'usecase' => '0'
                ]
            ];
            $question->answers['1']->fraction = '1.00';
            $question->answers['2']->fraction = '0.00';
        } else if ($question->qtype == 'someothertypewithnoanswers') {
            $question->answers = [];
        } else if ($question->qtype == 'truefalse') {
            $question->answers = [
                '1' => (object)[
                    'id' => '1',
                    'answer' => 'True',
                    'fraction' => '1.00'
                ],
                '2' => (object)[
                    'id' => '2',
                    'answer' => 'False',
                    'fraction' => '0.00'
                ]
            ];
        }

        if ($index == '10') {
            $question->questiontext = 'test question 2';
            $question->answers = [
                '1' => (object)[
                    'id' => '4',
                    'answer' => 'test answer 4',
                    'fraction' => '1.00'
                ]
            ];
        }
        return $question;
    }

    protected function assert_outputs($input, $output) {
        // Output is an associative array.
        $this->assertEquals(0, count(array_filter(array_keys($output), 'is_string')));
        $this->assertEquals(count($input['questions']) , count($output));
    }

    protected function assert_output($input, $output) {
        parent::assert_output($input, $output);
        $questionindex = intval(substr($output['question_name'], 14, 2), 10);
        $attemptquestions = $input['attempt']->questions[$questionindex];

        $this->assert_attempt($input['attempt'], $output);
        $this->assert_question($input['questions'][$questionindex], $output);
        $this->assert_question_attempt($attemptquestions, $output, $input['questions'][$questionindex]);
    }

    protected function assert_attempt($input, $output) {
        $this->assertEquals($input->url, $output['attempt_url']);
        $this->assertEquals($input->name, $output['attempt_name']);
        $this->assertEquals(static::$xapitype.$input->type, $output['attempt_type']);
    }

    protected function assert_question_attempt($input, $output, $question) {
        $this->assertEquals((float) $input->maxmark, $output['attempt_score_max']);
        $this->assertEquals(0, $output['attempt_score_min']);
        $this->assertEquals((float) $input->steps[1]->fraction, $output['attempt_score_scaled']);
        $this->assertEquals(((float) $input->maxmark) * ((float) $input->steps[1]->fraction), $output['attempt_score_raw']);
        $this->assertEquals(true, $output['attempt_completed']);
        $this->assertEquals(true, $output['attempt_success']);

        $numerictypes = [
            'numerical',
            'calculated',
            'calculatedmulti',
            'calculatedsimple'
        ];

        $matchtypes = [
            'randomsamatch',
            'match'
        ];

        $fillintypes = [
            'shortanswer'
        ];

        $noanswer = [
            'someothertypewithnoanswers'
        ];

        if (in_array($question->qtype, $matchtypes)) {
            $this->assertEquals(
                'moodle_quiz_question_source_1[.]moodle_quiz_question_target_1[,]' .
                'moodle_quiz_question_source_4[.]moodle_quiz_question_target_4',
                $output['interaction_correct_responses'][0]
            );
        } else if (in_array($question->qtype, $numerictypes)) {
             $this->assertEquals('4[:]6', $output['interaction_correct_responses'][0]);
        } else if (in_array($question->qtype, $fillintypes)) {
            $this->assertEquals(
                '{case_matters=false}'.$question->answers['1']->answer,
                $output['interaction_correct_responses'][0]
            );
        } else if ($question->qtype == 'truefalse') {
            $this->assertEquals(
                strtolower($question->answers['1']->answer),
                $output['interaction_correct_responses'][0]
            );
        } else if (!in_array($question->qtype, $noanswer)) {
            // Multichoice.
            $this->assertEquals(
                'moodle_quiz_question_answer_1[,]moodle_quiz_question_answer_2',
                $output['interaction_correct_responses'][0]
            );
        } else {
            // Default.
            $this->assertEquals($input->rightanswer, $output['interaction_correct_responses'][0]);
        }

        // For the purposes of testing, the response is always correct. Test that the format is right.
        if (in_array($question->qtype, $numerictypes) || in_array($question->qtype, $fillintypes)) {
            $this->assertEquals($input->responsesummary, $output['attempt_response']);
        } else {
            $this->assertEquals($output['interaction_correct_responses'][0], $output['attempt_response']);
        }

    }

    protected function assert_question($input, $output) {
        if (strpos($input->qtype, 'calculated') === 0) {
            $this->assertEquals($input->url.'&variant=1', $output['question_url']);
            $this->assertEquals($input->name . ' - variant 1', $output['question_name']);
            $this->assertEquals($input->questiontext . ' - variant 1', $output['question_description']);
        } else {
            $this->assertEquals($input->url, $output['question_url']);
            $this->assertEquals($input->name, $output['question_name']);
            $this->assertEquals($input->questiontext, $output['question_description']);
        }

        $numerictypes = [
            'numerical',
            'calculated',
            'calculatedmulti',
            'calculatedsimple'
        ];

        $matchtypes = [
            'randomsamatch',
            'match'
        ];

        $fillintypes = [
            'shortanswer'
        ];

        $multitypes = [
            'somecustomquestiontypethatsnotstandardinmoodle',
            'multichoice'
        ];

        if (in_array($input->qtype, $matchtypes)) {
            $this->assertEquals(
                'test question',
                $output['interaction_target']['moodle_quiz_question_target_1']
            );
            $this->assertEquals(
                'test answer',
                $output['interaction_source']['moodle_quiz_question_source_1']
            );
            $this->assertEquals(
                'test question 2',
                $output['interaction_target']['moodle_quiz_question_target_4']
            );
            $this->assertEquals(
                'test answer 4',
                $output['interaction_source']['moodle_quiz_question_source_4']
            );
            $this->assertEquals('matching', $output['interaction_type']);
        } else if (in_array($input->qtype, $multitypes)) {
            $this->assertEquals($input->answers['2']->answer, $output['interaction_choices']['moodle_quiz_question_answer_2']);
            $this->assertEquals('choice', $output['interaction_type']);
        } else if ($input->qtype == 'truefalse') {
            $this->assertEquals('true-false', $output['interaction_type']);
        } else if (in_array($input->qtype, $numerictypes)) {
            $this->assertEquals('numeric', $output['interaction_type']);
        } else if (in_array($input->qtype, $fillintypes)) {
            $this->assertEquals('fill-in', $output['interaction_type']);
        } else {
            $this->assertEquals('other', $output['interaction_type']);
        }
    }
}
