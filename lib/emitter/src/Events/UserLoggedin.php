<?php namespace XREmitter\Events;

class UserLoggedin extends Event {
    protected static $verb_display = [
        'en' => 'logged in to'
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
                'id' => 'https://brindlewaye.com/xAPITerms/verbs/loggedin/',
                'display' => $this->readVerbDisplay($opts),
            ],
            'object' => $this->readApp($opts),
        ]);
    }
}