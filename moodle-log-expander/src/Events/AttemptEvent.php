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
        $grade_items = $this->repo->readGradeItems($attempt->quiz, 'quiz');
        $attempt->questions = $this->repo->readQuestionAttempts($attempt->id);
        $questions = $this->repo->readQuestions($attempt->quiz);

        return array_merge(parent::read($opts), [
            'attempt' => $attempt,
            'module' => $this->repo->readModule($attempt->quiz, 'quiz'),
            'grade_items' => $grade_items,
            'questions' => $questions
        ]);
    }
}