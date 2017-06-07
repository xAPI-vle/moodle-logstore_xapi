<?php namespace XREmitter\Events;

class UserRegistered extends Event {
    protected static $verb_display = [
        'en' => 'registered to'
    ];

    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        return array_merge(parent::read($opts), [
            'verb' => [
                'id' => 'http://adlnet.gov/expapi/verbs/registered',
                'display' => $this->readVerbDisplay($opts),
            ],
            'object' => $this->readApp($opts),
        ]);
    }
}