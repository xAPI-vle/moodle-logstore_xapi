<?php namespace LogExpander\Events;

class AssignmentGraded extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        $grade = $this->repo->readObject($opts['objectid'], $opts['objecttable']);
        return array_merge(parent::read($opts), [
            'grade' => $grade,
            'graded_user' => $this->repo->readUser($grade->userid),
            'module' => $this->repo->readModule($grade->assignment, 'assign'),
        ]);
    }
}