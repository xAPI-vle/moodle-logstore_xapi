<?php namespace MXTranslator\Events;

class FeedbackSubmitted extends ModuleViewed {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override AttemtStarted
     */
    public function read(array $opts) {

        $feedback = $this->parseFeedback($opts);

        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'attempt_completed',
            'attempt_url' => $opts['attempt']->url,
            'attempt_type' => static::$xapiType.$opts['attempt']->type,
            'attempt_ext' => $opts['attempt'],
            'attempt_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_feedback_attempt',
            'attempt_name' => $opts['attempt']->name,
            'attempt_score_raw' => $feedback->score->raw,
            'attempt_score_min' => $feedback->score->min,
            'attempt_score_max' => $feedback->score->max,
            'attempt_score_scaled' => $feedback->score->scaled,
            'attempt_success' => null,
            'attempt_completed' => true,
            'attempt_duration' => null,
            'time' => date('c', $opts['attempt']->timemodified)
        ])];
    }

    /**
     * Converts a outputs feedback question and result data in a more manageable format
     * @param [Array => Mixed] $opts
     * @return [PHPObj => Mixed]
     */
    public function parseFeedback($opts){
        $parsedQuestions = [];
        $scoreMax = 0;
        $scoreRaw = 0;

        foreach ($opts['questions'] as $item => $question) {
            // Find the response to the current question
            $currentResponse = null;
            foreach ($opts['attempt']->responses as $responseId => $response) {
                if (!empty($response->item) && $response->item == $item) {
                    $currentResponse = $response;
                }
            }

            if (is_null($currentResponse)) {
                // Perhaps a label or the learner did not answer this question - don't add to the array.
                break;
            }

            // Parse the current question
            $parsedQuestion = (object)[
                'question' => $question,
                'options' => $this->parseQuestionPresentation($question->presentation, $question->typ),
                'score' => (object) [
                    'max' => 0,
                    'raw' => 0
                ],
                'response' => null
            ];

            $parsedQuestion->response = $currentResponse->id;

            // Add scores and response
            foreach ($parsedQuestion->options as $optionIndex => $option) {
                if (isset($option->value) && $option->value > $parsedQuestion->score->max) {
                    $parsedQuestion->score->max = $option->value;
                }

                // Find the option the learner selected
                if ($optionIndex == $currentResponse->id){
                    if (isset($option->value)) {
                        $parsedQuestion->score->raw = $option->value;
                    }
                }
            }

            $scoreMax += $parsedQuestion->score->max;
            $scoreRaw += $parsedQuestion->score->raw;

            if ($parsedQuestion->score->max == 0) {
                $parsedQuestion->score->max = null;
                $parsedQuestion->score->raw = null;
            }
            else {
                $parsedQuestion->score->min = 0;
                $parsedQuestion->score->scaled = $parsedQuestion->score->raw / $parsedQuestion->score->max;
            }

            array_push(
                $parsedQuestions, 
                $parsedQuestion
            );
        }

        $scoreMin = null;
        $scoreScaled = null;
        if ($scoreMax == 0){
            $scoreMax = null;
            $scoreRaw = null;
        }
        else {
            $scoreScaled = $scoreRaw / $scoreMax;
            $scoreMin = 0;
        }

        return (object)[
            'questions' => $parsedQuestions,
            'score' => (object) [
                'max' => $scoreMax,
                'raw' => $scoreRaw,
                'min' => $scoreMin,
                'scaled' => $scoreScaled
            ] 
        ];
    }

    /**
     * Converts a feedback item "presentation" string into an array
     * @param [String => Mixed] $presentation
     * @param [String => Mixed] $type
     * @return [Array => Mixed]
     */
    protected function parseQuestionPresentation ($presentation, $type){

        // Text areas don't have options or scores
        if ($type == 'textarea') {
            return [];
        }

        // Strip out the junk.
        $presentation = str_replace('r>>>>>', '', $presentation);
        $presentation = trim(preg_replace('/\s+/', ' ', $presentation));
        $presentation = strip_tags($presentation);

        $options = explode('|', $presentation);
        $return = [(object)[
            'description' => 'Not selected'
        ]];

        foreach ($options as $index => $option) {
            switch ($type) {
                case 'multichoice':
                    array_push($return, (object)[
                        'description' => $option
                    ]);
                    break;
                case 'multichoicerated':
                    $optionArr = explode('#### ', $option);
                    array_push($return, (object)[
                        'description' => $optionArr[1],
                        'value' => $optionArr[0]
                    ]);
                    break;
                default:
                    // Unsupported type. 
                    return [];
                    break;
            }
        }

        return $return;
    }

}