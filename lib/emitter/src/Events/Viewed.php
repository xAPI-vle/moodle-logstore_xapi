<?php namespace XREmitter\Events;

abstract class Viewed extends Event {
    protected static $verb_display = [
        'en' => 'viewed'
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
                'id' => 'http://id.tincanapi.com/verb/viewed',
                'display' => $this->readVerbDisplay($opts),
            ],
        ]);
    }
}