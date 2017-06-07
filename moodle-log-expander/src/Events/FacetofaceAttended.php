<?php namespace LogExpander\Events;

class FacetofaceAttended extends FacetofaceEvent {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        $session = $this->repo->readFacetofaceSession($opts['objectid']);
        return array_merge(parent::read($opts), [
            'signups' => $this->repo->readFacetofaceSessionSignups($opts['objectid'], $opts['timecreated'])
        ]);
    }
}