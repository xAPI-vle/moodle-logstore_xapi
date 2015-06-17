<?php namespace XREmitter\Events;

class AttemptCompleted extends AttemptStarted {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        return array_merge(parent::read($opts), [
            'verb' => [
                'id' => 'http://adlnet.gov/expapi/verbs/completed',
                'display' => [
                    'en-GB' => 'completed',
                    'en-US' => 'completed',
                ],
            ],
            'result' => [
                'score' => [
                    'raw' => $opts['attempt_result'],
                ],
                'completion' => $opts['attempt_completed'],
                'duration' => $opts['attempt_duration'],
            ],
        ]);
    }
}