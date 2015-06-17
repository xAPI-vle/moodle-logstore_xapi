<?php namespace XREmitter\Events;

class AttemptStarted extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        return array_merge_recursive(parent::read($opts), [
            'verb' => [
                'id' => 'http://activitystrea.ms/schema/1.0/start',
                'display' => [
                    'en-GB' => 'started',
                    'en-US' => 'started',
                ],
            ],
            'object' => [
                'id' => $opts['attempt_url'],
                'definition' => [
                    'type' => 'http://activitystrea.ms/schema/1.0/page',
                    'name' => [
                        'en-GB' => $opts['attempt_name'],
                        'en-US' => $opts['attempt_name'],
                    ],
                    'extensions' => [
                        $opts['attempt_ext_key'] => $opts['attempt_ext']
                    ],
                ],
            ],
            'context' => [
                'contextActivities' => [
                    'grouping' => [
                        $this->readCourse($opts),
                        $this->readModule($opts),
                    ],
                ],
            ],
        ]);
    }
}