<?php namespace LogExpander\Events;

class ScormLaunched extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        $scorm_scoes = $this->repo->readObject($opts['objectid'], $opts['objecttable']);
        return array_merge(parent::read($opts), [
            'module' => $this->repo->readModule($scorm_scoes->scorm, 'scorm'),
            'scorm_scoes' => $scorm_scoes
        ]);
    }
}