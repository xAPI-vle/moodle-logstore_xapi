<?php namespace XREmitter\Events;

class UserLoggedout extends Event {
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
                'display' => [
                    'en-GB' => 'logged out of',
                    'en-US' => 'logged out of',
                ],
            ],
            'object' => $this->readApp($opts),
        ]);
    }
}