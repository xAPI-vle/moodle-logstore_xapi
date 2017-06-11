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
        $gradeComment = $this->repo->readGradeComment($grade->id, $grade->assignment)->commenttext;
        $gradeItems = $this->repo->readGradeItems($grade->assignment, 'assign');
        return array_merge(parent::read($opts), [
            'grade' => $grade,
            'grade_comment' => $gradeComment,
            'grade_items' => $gradeItems,
            'graded_user' => $this->repo->readUser($grade->userid),
            'module' => $this->repo->readModule($grade->assignment, 'assign'),
        ]);
    }
}