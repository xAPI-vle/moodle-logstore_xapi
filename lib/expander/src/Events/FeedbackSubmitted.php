<?php namespace LogExpander\Events;

class FeedbackSubmitted extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {

        $attempt = $this->repo->readFeedbackAttempt($opts['objectid']);
        $attempt->timemodified = $this->timeSelector($attempt);
        return array_merge(parent::read($opts), [
            'module' => $this->repo->readModule($attempt->feedback, 'feedback'),
            'questions' => $this->repo->readFeedbackQuestions($attempt->feedback),
            'attempt' => $attempt,
        ]);
    }

    /**
     * Checks to see which time element in $attempt is valid and if none are available
     * returns time()
     * @param $attempt
     * @return int
     */
    private function timeSelector($attempt) {

        $retValue = time();
        if (!empty($attempt->timemodified)) {
            $retValue = $attempt->timemodified;
        } else if (!empty($attempt->timefinished)) {
            $retValue = $attempt->timefinished;
        } else if (!empty($attempt->timestarted)) {
            $retValue = $attempt->timestarted;
        }

        return $retValue;
    }
}