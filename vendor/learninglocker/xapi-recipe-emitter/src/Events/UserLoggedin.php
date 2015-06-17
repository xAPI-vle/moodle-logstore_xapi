<?php namespace XREmitter\Events;

class UserLoggedin extends Event {
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
                'display' => [
                    'en-GB' => 'logged in to',
                    'en-US' => 'logged in to',
                ],
            ],
            'object' => $this->readApp($opts),
        ]);
    }
}