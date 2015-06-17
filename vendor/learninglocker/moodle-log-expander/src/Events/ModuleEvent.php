<?php namespace LogExpander\Events;

class ModuleEvent extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        return array_merge(parent::read($opts), [
            'module' => $this->repo->readModule($opts['objectid'], $opts['objecttable']),
        ]);
    }
}