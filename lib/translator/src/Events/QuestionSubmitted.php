<?php namespace MXTranslator\Events;

class QuestionSubmitted extends AttemptStarted {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override AttemtStarted
     */
    public function read(array $opts) {
        $translatorEvents = [];

        // Push question statements to $translatorEvents['events'].
        foreach ($opts['attempt']->questions as $questionId => $questionAttempt) {
            $question = $this->expandQuestion(
                $opts['questions'][$questionAttempt->questionid],
                $opts['questions']
            );
            array_push(
                $translatorEvents,
                $this->questionStatement(
                    parent::read($opts)[0],
                    $questionAttempt,
                    $question
                )
            );
        }

        return $translatorEvents;
    }

    /**
     * For certain question types, expands question data by pulling from other questions. 
     * @param PHPObj $question
     * @param Array $questions
     * @return PHPObj $question
     */
    protected function expandQuestion($question, $questions) {
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
     * @param PHPObj $questionAttempt
     * @param PHPObj $question
     * @return [String => Mixed]
     */
    protected function questionStatement($template, $questionAttempt, $question) {

        // For questions, only include data relevant to the current question in the attempt extension. 
        $template['attempt_ext']->questions = [$questionAttempt];

        $translatorEvent = [
            'recipe' => 'attempt_question_completed',
            'attempt_ext' => $template['attempt_ext'],
            'question_attempt_ext' => $questionAttempt,
            'question_attempt_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_question_attempt',
            'question_ext' => $question,
            'question_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_question',
            'question_name' => $question->name ?: 'A Moodle quiz question',
            'question_description' => strip_tags($question->questiontext) ?: 'A Moodle quiz question',
            'question_url' => $question->url,
            'attempt_score_scaled' => 0, //default
            'attempt_score_raw' => 0, //default
            'attempt_score_min' => 0, //always 0
            'attempt_score_max' => isset($questionAttempt->maxmark) ? floatval($questionAttempt->maxmark) : 100,
            'attempt_response' => $questionAttempt->responsesummary, //default
            'interaction_correct_responses' => [$questionAttempt->rightanswer], //default
            'interaction_type' => 'other', //default
        ];

        $submittedState = $this->getLastState($questionAttempt);

        if (!is_null($submittedState->timestamp)) {
            $translatorEvent['time'] = date('c', $submittedState->timestamp);
        }

        $translatorEvent = $this->resultFromState($translatorEvent, $questionAttempt, $submittedState);

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
             $translatorEvent = $this->matchStatement($translatorEvent, $questionAttempt, $question);
        } else if (in_array($question->qtype, $numerictypes)) {
             $translatorEvent = $this->numericStatement($translatorEvent, $questionAttempt, $question);
        } else if (in_array($question->qtype, $fillintypes)) {
             $translatorEvent = $this->shortanswerStatement($translatorEvent, $questionAttempt, $question);
        } else if (!is_null($question->answers) && ($question->answers !== [])) {
            $translatorEvent = $this->multichoiceStatement($translatorEvent, $questionAttempt, $question);
        }

        if (strpos($question->qtype, 'calculated') === 0) {
            $translatorEvent['question_url'] .= '&variant='.$questionAttempt->variant;
            $translatorEvent['question_name'] .= ' - variant '.$questionAttempt->variant;
            $translatorEvent['question_description'] .= ' - variant '.$questionAttempt->variant;
        }

        return array_merge($template, $translatorEvent);
    }

    /**
     * Add some result data to translator event for an individual question attempt based on Moodle's question attempt state
     * @param [String => Mixed] $translatorEvent
     * @param PHPObj $questionAttempt
     * @param PHPObj $submittedState
     * @return [String => Mixed]
     */
    public function resultFromState($translatorEvent, $questionAttempt, $submittedState) {
        $maxMark = isset($questionAttempt->maxmark) ? $questionAttempt->maxmark : 100;
        $scaledScore = $submittedState->fraction;
        $rawScore = $scaledScore * floatval($maxMark);

        switch ($submittedState->state) {
            case "todo":
                $translatorEvent['attempt_completed'] = false;
                $translatorEvent['attempt_success'] = null;
                break;
            case "gaveup":
                $translatorEvent['attempt_completed'] = false;
                $translatorEvent['attempt_success'] = false;
                break;
            case "complete":
                $translatorEvent['attempt_completed'] = true;
                $translatorEvent['attempt_success'] = null;
                break;
            case "gradedwrong":
                $translatorEvent['attempt_completed'] = true;
                $translatorEvent['attempt_success'] = false;
                $translatorEvent['attempt_score_scaled'] = $scaledScore;
                $translatorEvent['attempt_score_raw'] = $rawScore;
                break;
            case "gradedpartial":
                $translatorEvent['attempt_completed'] = true;
                $translatorEvent['attempt_success'] = false;
                $translatorEvent['attempt_score_scaled'] = $scaledScore;
                $translatorEvent['attempt_score_raw'] = $rawScore;
                break;
            case "gradedright":
                $translatorEvent['attempt_completed'] = true;
                $translatorEvent['attempt_success'] = true;
                $translatorEvent['attempt_score_scaled'] = $scaledScore;
                $translatorEvent['attempt_score_raw'] = $rawScore;
                break;
            default:
                $translatorEvent['attempt_completed'] = null;
                $translatorEvent['attempt_success'] = null;
                break;
        }

        return $translatorEvent;
    }

    /**
     * Add data specifc to multichoice and true/false question types to a translator event.
     * @param [String => Mixed] $translatorEvent
     * @param PHPObj $questionAttempt
     * @param PHPObj $question
     * @return [String => Mixed]
     */
    public function multichoiceStatement($translatorEvent, $questionAttempt, $question) {
        $choices = [];
        foreach ($question->answers as $answer) {
            $choices['moodle_quiz_question_answer_'.$answer->id] = strip_tags($answer->answer);
        }

        // If there are answers, assume multiple choice until proven otherwise.
        $translatorEvent['interaction_type'] = 'choice';
        $translatorEvent['interaction_choices'] = $choices;

        $responses = [];
        $correctResponses = [];

        // We can't simply explode $questionAttempt->responsesummary because responses may contain "; ". 
        foreach ($choices as $answerId => $choice) {
            if ($this->inResponsesSummary($questionAttempt->responsesummary, $choice, '; ', '; ')) {
                array_push($responses, $answerId);
            }
            if (!(strpos($questionAttempt->rightanswer, $choice) === false)) {
                array_push($correctResponses, $answerId);
            }
        }

        if ($responses != []) {
            $translatorEvent['attempt_response'] = implode('[,]', $responses);
        }

        $translatorEvent['interaction_correct_responses'] = [implode('[,]', $correctResponses)];

        // Special handling of true-false question type (some overlap with multichoice).
        if ($question->qtype == 'truefalse') {
            $translatorEvent['interaction_type'] = 'true-false';
            $translatorEvent['interaction_choices'] = null;

            if ($questionAttempt->responsesummary == 'True') {
                $translatorEvent['attempt_response'] = 'true';
            } else if ($questionAttempt->responsesummary == 'False') {
                $translatorEvent['attempt_response'] = 'false';
            }

            if ($questionAttempt->rightanswer == 'True') {
                $translatorEvent['interaction_correct_responses'] = ['true'];
            } else if ($questionAttempt->rightanswer == 'False') {
                $translatorEvent['interaction_correct_responses'] = ['false'];
            }
        }


        return $translatorEvent;
    }

    /**
     * Add data specifc to numeric question types to a translator event.
     * @param [String => Mixed] $translatorEvent
     * @param PHPObj $questionAttempt
     * @param PHPObj $question
     * @return [String => Mixed]
     */
    public function numericStatement($translatorEvent, $questionAttempt, $question) {

        $translatorEvent['interaction_type'] = 'numeric';


        $correctAnswerId = null;
        foreach ($question->answers as $answer) {
            if (intval($answer->fraction) === 1) {
                $correctAnswerId = $answer->id;
            }
        }

        $tolerance = 0;
        $toleranceType = 2;
        $answersdata = [];
        if ($question->qtype == "numerical") {
            $answersdata = $question->numerical->answers;
        } else if (strpos($question->qtype, 'calculated') === 0) {
            $answersdata = $question->calculated->answers;
        }

        if (!is_null($correctAnswerId) && count($answersdata) > 0) {
            foreach ($answersdata as $answerdata) {
                if(isset($answerdata->answer)){
                    if ($answerdata->answer == $correctAnswerId) {
                        $tolerance = floatval($answerdata->tolerance);
                        if (isset($answerdata->tolerancetype)) {
                            $toleranceType = intval($answerdata->tolerancetype);
                        }
                    }
                }
            }
        }

        $rigthtanswer = floatval($questionAttempt->rightanswer);
        if ($tolerance > 0) {
            $toleranceMax = $rigthtanswer + $tolerance;
            $toleranceMin = $rigthtanswer - $tolerance;
            switch ($toleranceType) {
                case 1:
                    $toleranceMax = $rigthtanswer + ($rigthtanswer * $tolerance);
                    $toleranceMin = $rigthtanswer - ($rigthtanswer * $tolerance);
                    break;
                case 3:
                    $toleranceMax = $rigthtanswer + ($rigthtanswer * $tolerance);
                    $toleranceMin = $rigthtanswer / (1 + $tolerance);
                    break;
                default:
                    break;
            }
            $rigthtanswerstring = strval($toleranceMin) . '[:]' . strval($toleranceMax);
            $translatorEvent['interaction_correct_responses'] = [$rigthtanswerstring];
        } else {
            $translatorEvent['interaction_correct_responses'] = [$questionAttempt->rightanswer];
        }

        return $translatorEvent;
    }

    /**
     * Add data specifc to shortanswer question types to a translator event.
     * @param [String => Mixed] $translatorEvent
     * @param PHPObj $questionAttempt
     * @param PHPObj $question
     * @return [String => Mixed]
     */
    public function shortanswerStatement($translatorEvent, $questionAttempt, $question) {

        $translatorEvent['interaction_type'] = 'fill-in';
        $translatorEvent['interaction_correct_responses'] = [];

        foreach ($question->answers as $answer) {
            if (intval($answer->fraction) === 1) {
                $correctResponse;
                if ($question->shortanswer->options->usecase == '1') {
                    $correctResponse = '{case_matters=true}'.$answer->answer;
                } else {
                    $correctResponse = '{case_matters=false}'.$answer->answer;
                }
                array_push($translatorEvent['interaction_correct_responses'], $correctResponse);
            }
        }

        return $translatorEvent;
    }

    /**
     * Add data specifc to matching question types to a translator event.
     * @param [String => Mixed] $translatorEvent
     * @param PHPObj $questionAttempt
     * @param PHPObj $question
     * @return [String => Mixed]
     */
    public function matchStatement($translatorEvent, $questionAttempt, $question) {

        $translatorEvent['interaction_type'] = 'matching';

        $targets = [];
        $sources = [];
        $correctResponses = [];
        $responseTargetsPos = [];
        $responseSourcesPos = [];

        foreach ($question->match->subquestions as $subquestion) {
            $target = strip_tags($subquestion->questiontext);
            $source = strip_tags($subquestion->answertext);
            $targetId = 'moodle_quiz_question_target_'.$subquestion->id;
            $sourceId = 'moodle_quiz_question_source_'.$subquestion->id;
            $targets[$targetId] = $target;
            $sources[$sourceId] = $source;
            array_push(
                $correctResponses, 
                $sourceId.'[.]'.$targetId
            );

            // Get the positions of the target and source within the response string.
            $responseTargetsPos[strpos($questionAttempt->responsesummary, $target)] = $targetId;
            $responseSourcesPos[strpos($questionAttempt->responsesummary, $source)] = $sourceId;
        }

        // Get ordered and indexed lists of target and source.
        ksort($responseTargetsPos);
        $responseTargets = array_values($responseTargetsPos);
        ksort($responseSourcesPos);
        $responseSources = array_values($responseSourcesPos);

        $translatorEvent['attempt_response'] = '';
        if (count($responseTargets) == count($responseSources) && count($responseTargets) > 0) {
            $responses = [];
            foreach ($responseTargets as $index => $targetId) {
                array_push(
                    $responses,
                    $responseSources[$index].'[.]'.$targetId
                );
            }
            $translatorEvent['attempt_response'] = implode('[,]', $responses);
        }

        $translatorEvent['interaction_target'] = $targets;
        $translatorEvent['interaction_source'] = $sources;
        $translatorEvent['interaction_correct_responses'] = [implode('[,]', $correctResponses)];

        return $translatorEvent;
    }

    /**
     * Get pertient data from the last recorded step of a learners interactions within a question attempt.
     * @param PHPObj $questionAttempt
     * @return [String => Mixed]
     */
    private function getLastState($questionAttempt) {

        // Default placeholder to -1 so that the first item we check will always be greater than the placeholder.
        $sequencenumber = -1;

        // Default state in case there are no steps.
        $state = (object)[
            "state" => "todo",
            "timestamp" => null
        ];

        // Cycle through steps to find the last one (the one with the highest sequence number).
        foreach ($questionAttempt->steps as $stepId => $step) {
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

    private function inResponsesSummary($haystack, $needle, $leftDelim, $rightDelim) {
        $needlePos = strpos($haystack, $needle);
        if (
            // Check if choice is contained in the learner's response
            !($needlePos === false)
            // Check choice is prefixed with left delimiter or at start of string.
            && (
                ($needlePos == 0)
                || (substr($haystack, $needlePos - strlen($leftDelim), strlen($leftDelim)) == $leftDelim)
            )
            // Check choice is follow by right delimiter or at end of string.
            && (
                ($needlePos == strlen($haystack) - strlen($needle))
                || (substr($haystack, $needlePos + strlen($needle), strlen($rightDelim)) == $rightDelim)
            )
        ) {
            return true;
        }
        return false;
    }
}