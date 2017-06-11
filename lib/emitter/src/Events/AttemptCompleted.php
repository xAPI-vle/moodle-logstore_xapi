<?php namespace XREmitter\Events;

class AttemptCompleted extends Event {
    protected static $verbDisplay = [
        'en' => 'completed'
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
                'id' => 'http://adlnet.gov/expapi/verbs/completed',
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
                'duration' => $opts['attempt_duration'],
            ],
            'object' => [
                'id' => $opts['module_url'],
                'definition' => [
                    'type' => $opts['module_type'],
                    'name' => [
                        $opts['context_lang'] => $opts['module_name'],
                    ],
                    'description' => [
                        $opts['context_lang'] => $opts['module_description'],
                    ],
                ],
            ],
            'context' => [
                'contextActivities' => [
                    'grouping' => [
                        $this->readCourse($opts),
                        [
                            'id' => $opts['attempt_url'],
                            'definition' => [
                                'type' => $opts['attempt_type'],
                                'name' => [
                                    $opts['context_lang'] => $opts['attempt_name'],
                                ],
                                'extensions' => [
                                    $opts['attempt_ext_key'] => $opts['attempt_ext']
                                ],
                            ],
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