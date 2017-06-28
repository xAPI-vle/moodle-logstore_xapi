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

namespace MXTranslator\Events;

defined('MOODLE_INTERNAL') || die();

class QuestionSubmitted extends AttemptStarted {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override AttemtStarted
     */
    public function read(array $opts) {
        $translatorevents = [];

        // Push question statements to $translatorevents['events'].
        foreach ($opts['attempt']->questions as $questionid => $questionattempt) {
            $question = $this->expand_question(
                $opts['questions'][$questionattempt->questionid],
                $opts['questions']
            );
            array_push(
                $translatorevents,
                $this->question_statement(
                    parent::read($opts)[0],
                    $questionattempt,
                    $question
                )
            );
        }

        return $translatorevents;
    }

    /**
     * For certain question types, expands question data by pulling from other questions.
     * @param PHPObj $question
     * @param Array $questions
     * @return PHPObj $question
     */
    protected function expand_question($question, $questions) {
        if ($question->qtype == 'randomsamatch') {
            $subquestions = [];
            foreach ($questions as $otherquestion) {
                if ($otherquestion->qtype == 'shortanswer') {
                    foreach ($otherquestion->answers as $answer) {
                        if (intval($answer->fraction) === 1) {
                            array_push(
                                $subquestions,
                                (object) [
                                    "id" => $answer->id,
                                    "questiontext" => $otherquestion->questiontext,
                                    "answertext" => $answer->answer
                                ]
                            );
                            // Only take the first correct answer because that's what Moodle does.
                            break;
                        }
                    }
                }
            }

            $question->match = (object) [
                'subquestions' => $subquestions
            ];
        }
        return $question;
    }

    /**
     * Build a translator event for an individual question attempt.
     * @param [String => Mixed] $template
     * @param PHPObj $questionattempt
     * @param PHPObj $question
     * @return [String => Mixed]
     */
    protected function question_statement($template, $questionattempt, $question) {

        // For questions, only include data relevant to the current question in the attempt extension.
        $template['attempt_ext']->questions = [$questionattempt];

        $translatorevent = [
            'recipe' => 'attempt_question_completed',
            'attempt_ext' => $template['attempt_ext'],
            'question_attempt_ext' => $questionattempt,
            'question_attempt_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_question_attempt',
            'question_ext' => $question,
            'question_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_question',
            'question_name' => $question->name ?: 'A Moodle quiz question',
            'question_description' => strip_tags($question->questiontext) ?: 'A Moodle quiz question',
            'question_url' => $question->url,
            'attempt_score_scaled' => 0, // Default.
            'attempt_score_raw' => 0, // Default.
            'attempt_score_min' => 0, // Always 0.
            'attempt_score_max' => isset($questionattempt->maxmark) ? floatval($questionattempt->maxmark) : 100,
            'attempt_response' => $questionattempt->responsesummary, // Default.
            'interaction_correct_responses' => [$questionattempt->rightanswer], // Default.
            'interaction_type' => 'other', // Default.
        ];

        $submittedstate = $this->get_last_state($questionattempt);

        if (!is_null($submittedstate->timestamp)) {
            $translatorevent['time'] = date('c', $submittedstate->timestamp);
        }

        $translatorevent = $this->result_from_state($translatorevent, $questionattempt, $submittedstate);

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

        if (in_array($question->qtype, $matchtypes)) {
             $translatorevent = $this->match_statement($translatorevent, $questionattempt, $question);
        } else if (in_array($question->qtype, $numerictypes)) {
             $translatorevent = $this->numeric_statement($translatorevent, $questionattempt, $question);
        } else if (in_array($question->qtype, $fillintypes)) {
             $translatorevent = $this->shortanswer_statement($translatorevent, $questionattempt, $question);
        } else if (!is_null($question->answers) && ($question->answers !== [])) {
            $translatorevent = $this->multichoice_statement($translatorevent, $questionattempt, $question);
        }

        if (strpos($question->qtype, 'calculated') === 0) {
            $translatorevent['question_url'] .= '&variant=' . $questionattempt->variant;
            $translatorevent['question_name'] .= ' - variant ' . $questionattempt->variant;
            $translatorevent['question_description'] .= ' - variant ' . $questionattempt->variant;
        }

        return array_merge($template, $translatorevent);
    }

    /**
     * Add some result data to translator event for an individual question attempt based on Moodle's question attempt state
     * @param [String => Mixed] $translatorevent
     * @param PHPObj $questionattempt
     * @param PHPObj $submittedState
     * @return [String => Mixed]
     */
    public function result_from_state($translatorevent, $questionattempt, $submittedstate) {
        $maxmark = isset($questionattempt->maxmark) ? $questionattempt->maxmark : 100;
        $scaledscore = $submittedstate->fraction;
        $rawscore = $scaledscore * floatval($maxmark);

        switch ($submittedstate->state) {
            case "todo":
                $translatorevent['attempt_completed'] = false;
                $translatorevent['attempt_success'] = null;
                break;
            case "gaveup":
                $translatorevent['attempt_completed'] = false;
                $translatorevent['attempt_success'] = false;
                break;
            case "complete":
                $translatorevent['attempt_completed'] = true;
                $translatorevent['attempt_success'] = null;
                break;
            case "gradedwrong":
                $translatorevent['attempt_completed'] = true;
                $translatorevent['attempt_success'] = false;
                $translatorevent['attempt_score_scaled'] = $scaledscore;
                $translatorevent['attempt_score_raw'] = $rawscore;
                break;
            case "gradedpartial":
                $translatorevent['attempt_completed'] = true;
                $translatorevent['attempt_success'] = false;
                $translatorevent['attempt_score_scaled'] = $scaledscore;
                $translatorevent['attempt_score_raw'] = $rawscore;
                break;
            case "gradedright":
                $translatorevent['attempt_completed'] = true;
                $translatorevent['attempt_success'] = true;
                $translatorevent['attempt_score_scaled'] = $scaledscore;
                $translatorevent['attempt_score_raw'] = $rawscore;
                break;
            default:
                $translatorevent['attempt_completed'] = null;
                $translatorevent['attempt_success'] = null;
                break;
        }

        return $translatorevent;
    }

    /**
     * Add data specifc to multichoice and true/false question types to a translator event.
     * @param [String => Mixed] $translatorevent
     * @param PHPObj $questionattempt
     * @param PHPObj $question
     * @return [String => Mixed]
     */
    public function multichoice_statement($translatorevent, $questionattempt, $question) {
        $choices = [];
        foreach ($question->answers as $answer) {
            $choices['moodle_quiz_question_answer_'.$answer->id] = strip_tags($answer->answer);
        }

        // If there are answers, assume multiple choice until proven otherwise.
        $translatorevent['interaction_type'] = 'choice';
        $translatorevent['interaction_choices'] = $choices;

        $responses = [];
        $correctresponses = [];

        // We can't simply explode $questionAttempt->responsesummary because responses may contain "; ".
        foreach ($choices as $answerid => $choice) {
            if ($this->in_responses_summary($questionattempt->responsesummary, $choice, '; ', '; ')) {
                array_push($responses, $answerid);
            }
            if (!(strpos($questionattempt->rightanswer, $choice) === false)) {
                array_push($correctresponses, $answerid);
            }
        }

        if ($responses != []) {
            $translatorevent['attempt_response'] = implode('[,]', $responses);
        }

        $translatorevent['interaction_correct_responses'] = [implode('[,]', $correctresponses)];

        // Special handling of true-false question type (some overlap with multichoice).
        if ($question->qtype == 'truefalse') {
            $translatorevent['interaction_type'] = 'true-false';
            $translatorevent['interaction_choices'] = null;

            if ($questionattempt->responsesummary == 'True') {
                $translatorevent['attempt_response'] = 'true';
            } else if ($questionattempt->responsesummary == 'False') {
                $translatorevent['attempt_response'] = 'false';
            }

            if ($questionattempt->rightanswer == 'True') {
                $translatorevent['interaction_correct_responses'] = ['true'];
            } else if ($questionattempt->rightanswer == 'False') {
                $translatorevent['interaction_correct_responses'] = ['false'];
            }
        }

        return $translatorevent;
    }

    /**
     * Add data specifc to numeric question types to a translator event.
     * @param [String => Mixed] $translatorevent
     * @param PHPObj $questionattempt
     * @param PHPObj $question
     * @return [String => Mixed]
     */
    public function numeric_statement($translatorevent, $questionattempt, $question) {

        $translatorevent['interaction_type'] = 'numeric';

        $correctanswerid = null;
        foreach ($question->answers as $answer) {
            if (intval($answer->fraction) === 1) {
                $correctanswerid = $answer->id;
            }
        }

        $tolerance = 0;
        $tolerancetype = 2;
        $answersdata = [];
        if ($question->qtype == "numerical") {
            $answersdata = $question->numerical->answers;
        } else if (strpos($question->qtype, 'calculated') === 0) {
            $answersdata = $question->calculated->answers;
        }

        if (!is_null($correctanswerid) && count($answersdata) > 0) {
            foreach ($answersdata as $answerdata) {
                if (isset($answerdata->answer)) {
                    if ($answerdata->answer == $correctanswerid) {
                        $tolerance = floatval($answerdata->tolerance);
                        if (isset($answerdata->tolerancetype)) {
                            $tolerancetype = intval($answerdata->tolerancetype);
                        }
                    }
                }
            }
        }

        $rigthtanswer = floatval($questionattempt->rightanswer);
        if ($tolerance > 0) {
            $tolerancemax = $rigthtanswer + $tolerance;
            $tolerancemin = $rigthtanswer - $tolerance;
            switch ($tolerancetype) {
                case 1:
                    $tolerancemax = $rigthtanswer + ($rigthtanswer * $tolerance);
                    $tolerancemin = $rigthtanswer - ($rigthtanswer * $tolerance);
                    break;
                case 3:
                    $tolerancemax = $rigthtanswer + ($rigthtanswer * $tolerance);
                    $tolerancemin = $rigthtanswer / (1 + $tolerance);
                    break;
                default:
                    break;
            }
            $rigthtanswerstring = strval($tolerancemin) . '[:]' . strval($tolerancemax);
            $translatorevent['interaction_correct_responses'] = [$rigthtanswerstring];
        } else {
            $translatorevent['interaction_correct_responses'] = [$questionattempt->rightanswer];
        }

        return $translatorevent;
    }

    /**
     * Add data specifc to shortanswer question types to a translator event.
     * @param [String => Mixed] $translatorevent
     * @param PHPObj $questionattempt
     * @param PHPObj $question
     * @return [String => Mixed]
     */
    public function shortanswer_statement($translatorevent, $questionattempt, $question) {

        $translatorevent['interaction_type'] = 'fill-in';
        $translatorevent['interaction_correct_responses'] = [];

        foreach ($question->answers as $answer) {
            if (intval($answer->fraction) === 1) {
                $correctresponse;
                if ($question->shortanswer->options->usecase == '1') {
                    $correctresponse = '{case_matters=true}'.$answer->answer;
                } else {
                    $correctresponse = '{case_matters=false}'.$answer->answer;
                }
                array_push($translatorevent['interaction_correct_responses'], $correctresponse);
            }
        }

        return $translatorevent;
    }

    /**
     * Add data specifc to matching question types to a translator event.
     * @param [String => Mixed] $translatorevent
     * @param PHPObj $questionattempt
     * @param PHPObj $question
     * @return [String => Mixed]
     */
    public function match_statement($translatorevent, $questionattempt, $question) {

        $translatorevent['interaction_type'] = 'matching';

        $targets = [];
        $sources = [];
        $correctresponses = [];
        $responsetargetspos = [];
        $responsesourcespos = [];

        foreach ($question->match->subquestions as $subquestion) {
            $target = strip_tags($subquestion->questiontext);
            $source = strip_tags($subquestion->answertext);
            $targetid = 'moodle_quiz_question_target_'.$subquestion->id;
            $sourceid = 'moodle_quiz_question_source_'.$subquestion->id;
            $targets[$targetid] = $target;
            $sources[$sourceid] = $source;
            array_push(
                $correctresponses,
                $sourceid . '[.]' . $targetid
            );

            // Get the positions of the target and source within the response string.
            $responsetargetspos[strpos($questionattempt->responsesummary, $target)] = $targetid;
            $responsesourcespos[strpos($questionattempt->responsesummary, $source)] = $sourceid;
        }

        // Get ordered and indexed lists of target and source.
        ksort($responsetargetspos);
        $responsetargets = array_values($responsetargetspos);
        ksort($responsesourcespos);
        $responsesources = array_values($responsesourcespos);

        $translatorevent['attempt_response'] = '';
        if (count($responsetargets) == count($responsesources) && count($responsetargets) > 0) {
            $responses = [];
            foreach ($responsetargets as $index => $targetid) {
                array_push(
                    $responses,
                    $responsesources[$index] . '[.]' . $targetid
                );
            }
            $translatorevent['attempt_response'] = implode('[,]', $responses);
        }

        $translatorevent['interaction_target'] = $targets;
        $translatorevent['interaction_source'] = $sources;
        $translatorevent['interaction_correct_responses'] = [implode('[,]', $correctresponses)];

        return $translatorevent;
    }

    /**
     * Get pertient data from the last recorded step of a learners interactions within a question attempt.
     * @param PHPObj $questionattempt
     * @return [String => Mixed]
     */
    private function get_last_state($questionattempt) {

        // Default placeholder to -1 so that the first item we check will always be greater than the placeholder.
        $sequencenumber = -1;

        // Default state in case there are no steps.
        $state = (object)[
            "state" => "todo",
            "timestamp" => null
        ];

        // Cycle through steps to find the last one (the one with the highest sequence number).
        foreach ($questionattempt->steps as $stepid => $step) {
            if ($step->sequencenumber > $sequencenumber) {

                // Now this step has the highest sequence number we've seen.
                $sequencenumber = $step->sequencenumber;
                $state = (object)[
                    "state" => $step->state,
                    "timestamp" => $step->timecreated,
                    "fraction" => (is_null($step->fraction) || $step->fraction == '') ? 0 : floatval($step->fraction)
                ];
            }
        }
        return $state;
    }

    private function in_responses_summary($haystack, $needle, $leftdelim, $rightdelim) {
        $needlepos = strpos($haystack, $needle);
        if (
            // Check if choice is contained in the learner's response.
            !($needlepos === false)
            // Check choice is prefixed with left delimiter or at start of string.
            && (
                ($needlepos == 0)
                || (substr($haystack, $needlepos - strlen($leftdelim), strlen($leftdelim)) == $leftdelim)
            )
            // Check choice is follow by right delimiter or at end of string.
            && (
                ($needlepos == strlen($haystack) - strlen($needle))
                || (substr($haystack, $needlepos + strlen($needle), strlen($rightdelim)) == $rightdelim)
            )
        ) {
            return true;
        }
        return false;
    }
}
