<?php namespace MXTranslator\Events;

class AttemptAbandoned extends AttemptReviewed {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override AttemptReviewed
     */
    public function read(array $opts) {
        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'attempt_abandoned'
        ])];
    }
}