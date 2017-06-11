<?php namespace XREmitter\Events;

class Attended extends Event {
    protected static $verb_display = [
        'en' => 'attended'
    ];

    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        $statement = array_merge_recursive(parent::read($opts), [
            'verb' => [
                'id' => 'http://adlnet.gov/expapi/verbs/attended',
                'display' => $this->readVerbDisplay($opts),
            ],
            'object' => [
                'id' => $opts['session_url'],
                'definition' => [
                    'type' => $opts['session_type'],
                    'name' => [
                        $opts['context_lang'] => $opts['session_name'],
                    ],
                    'description' => [
                        $opts['context_lang'] => $opts['session_description'],
                    ]
                ],
            ],
            'result' => [
                'duration' => $opts['attempt_duration'],
                'completion' => $opts['attempt_completion']
            ],
            'context' => [
                'instructor' => $this->readUser($opts, 'user'),
                'contextActivities' => [
                    'grouping' => [
                        $this->readCourse($opts),
                    ],
                    'parent' => [
                        $this->readModule($opts),
                    ],
                    'category' => [
                        [
                            'id' => 'http://xapi.trainingevidencesystems.com/recipes/attendance/0_0_1#simple',
                            'definition' => [
                                'type' => 'http://id.tincanapi.com/activitytype/recipe'
                            ]
                        ]
                    ],
                ],
            ],
        ]);

        // Overwrite actor, don't merge it. 
        $statement['actor'] = $this->readUser($opts, 'attendee');

        return $statement;
    }
}