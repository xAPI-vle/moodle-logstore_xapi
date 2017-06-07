<?php namespace XREmitter\Events;

class AssignmentGraded extends Event {
    protected static $verb_display = [
        'en' => 'recieved grade for'
    ];

    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        $instructor = parent::read($opts)['actor'];
        $statement =  array_merge_recursive(parent::read($opts), [
            'verb' => [
                'id' => 'http://adlnet.gov/expapi/verbs/scored',
                'display' => $this->readVerbDisplay($opts),
            ],
            'result' => [
                'score' => [
                    'raw' => $opts['grade_score_raw'],
                    'min' => $opts['grade_score_min'],
                    'max' => $opts['grade_score_max'],
                    'scaled' => $opts['grade_score_scaled']
                ],
                'completion' => $opts['grade_completed'],
                'response' => $opts['grade_comment']
            ],
            'object' => $this->readModule($opts),
            'context' => [
                'contextActivities' => [
                    'parent' => [
                        $this->readCourse($opts),
                    ],
                ],
                'instructor' => $instructor
            ],
        ]);

        //Excluded from array merge to make sure that the actor is overwritten e.g. if a different IFI is used. 
        $statement['actor'] = [
            'objectType' => 'Agent',
            'name' => $opts['graded_user_name'],
            'account' => [
                'homePage' => $opts['graded_user_url'],
                'name' => $opts['graded_user_id'],
            ],
        ];

        if (!is_null($opts['grade_success'])) {
            $statement['result']['success'] = $opts['grade_success'];
        }

        return $statement;
    }
}