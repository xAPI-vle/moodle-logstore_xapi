<?php namespace XREmitter\Events;

class ScormLaunched extends Event {
    protected static $verb_display = [
        'en' => 'launched'
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
                'id' => 'http://adlnet.gov/expapi/verbs/launched',
                'display' => $this->readVerbDisplay($opts),
            ],
            'object' => $this->readModule($opts),
            'context' => [
                'contextActivities' => [
                    'grouping' => [
                        $this->readCourse($opts),
                    ],
                ],
            ],
        ]);
    }
}