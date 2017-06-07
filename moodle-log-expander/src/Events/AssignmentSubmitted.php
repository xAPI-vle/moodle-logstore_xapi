<?php namespace LogExpander\Events;

class AssignmentSubmitted extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        $submission = $this->repo->readObject($opts['objectid'], $opts['objecttable']);
        return array_merge(parent::read($opts), [
            'submission' => $submission,
            'module' => $this->repo->readModule($submission->assignment, 'assign'),
        ]);
    }
}