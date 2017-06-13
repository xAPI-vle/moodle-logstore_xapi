<?php namespace XREmitter\Events;

class EventUnenrol extends EventEnrol {
    protected static $verbDisplay = [
        'en' => 'unregistered from'
    ];

    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        $statement = parent::read($opts);
        $statement['verb'] = [
            'id' => 'http://id.tincanapi.com/verb/unregistered',
            'display' => $this->readVerbDisplay($opts),
        ];
        return $statement;
    }
}