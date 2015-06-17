<?php namespace LogExpander\Events;

class AttemptEvent extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        $attempt = $this->repo->readAttempt($opts['objectid']);
        return array_merge(parent::read($opts), [
            'attempt' => $attempt,
            'module' => $this->repo->readModule($attempt->quiz, 'quiz'),
        ]);
    }
}