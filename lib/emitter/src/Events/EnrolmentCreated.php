<?php namespace XREmitter\Events;

class EnrolmentCreated extends Event {
    protected static $verb_display = [
        'en' => 'enrolled onto'
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
                'id' => 'http://www.tincanapi.co.uk/verbs/enrolled_onto_learning_plan',
                'display' => $this->readVerbDisplay($opts),
            ],
            'object' => $this->readCourse($opts),
            'context' => [
                'instructor' => $this->readUser($opts, 'instructor'),
            ],
        ]);
    }
}