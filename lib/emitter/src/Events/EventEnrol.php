<?php namespace XREmitter\Events;

class EventEnrol extends Event {
    protected static $verbDisplay = [
        'en' => 'registered for'
    ];

    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        return array_merge_recursive(parent::read($opts), [
            'verb' => [
                'id' => 'http://adlnet.gov/expapi/verbs/registered',
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
            'context' => [
                'contextActivities' => [
                    'grouping' => [
                        $this->readCourse($opts),
                    ],
                    'parent' => [
                        $this->readModule($opts),
                    ],
                    'category' => [
                            [
                            'id' => 'http://xapi.trainingevidencesystems.com/recipes/attendance/0_0_1#detailed',
                            'definition' => [
                                'type' => 'http://id.tincanapi.com/activitytype/recipe'
                            ]
                        ]
                    ],
                ],
            ],
        ]);
    }
}