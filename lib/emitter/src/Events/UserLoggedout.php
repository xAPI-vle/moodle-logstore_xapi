<?php namespace XREmitter\Events;

class UserLoggedout extends Event {
    protected static $verb_display = [
        'en' => 'logged out of'
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
                'id' => 'https://brindlewaye.com/xAPITerms/verbs/loggedout/',
                'display' => $this->readVerbDisplay($opts),
            ],
            'object' => $this->readApp($opts),
        ]);
    }
}