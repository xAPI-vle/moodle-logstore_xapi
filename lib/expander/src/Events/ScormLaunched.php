<?php namespace LogExpander\Events;

class ScormLaunched extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        $scormScoes = $this->repo->readObject($opts['objectid'], $opts['objecttable']);
        return array_merge(parent::read($opts), [
            'module' => $this->repo->readModule($scormScoes->scorm, 'scorm'),
            'scorm_scoes' => $scormScoes
        ]);
    }
}