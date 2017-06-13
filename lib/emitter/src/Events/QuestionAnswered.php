<?php namespace XREmitter\Events;

class QuestionAnswered extends Event {
    protected static $verbDisplay = [
        'en' => 'answered'
    ];

    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {

        $statement = [
            'verb' => [
                'id' => 'http://adlnet.gov/expapi/verbs/answered',
                'display' => $this->readVerbDisplay($opts),
            ],
            'result' => [
                'score' => [
                    'raw' => $opts['attempt_score_raw'],
                    'min' => $opts['attempt_score_min'],
                    'max' => $opts['attempt_score_max'],
                    'scaled' => $opts['attempt_score_scaled']
                ],
                'completion' => $opts['attempt_completed'],
                'response' => $opts['attempt_response']
            ],
            'object' => $this->readQuestion($opts),
            'context' => [
                'contextActivities' => [
                    'parent' => [
                        $this->readModule($opts)
                    ],
                    'grouping' => [
                        $this->readCourse($opts),
                        [
                            'id' => $opts['attempt_url']
                        ],
                    ],
                ],
            ],
        ];

        if (!is_null($opts['attempt_success'])) {
            $statement['result']['success'] = $opts['attempt_success'];
        }

        return array_merge_recursive(parent::read($opts), $statement);
    }
}