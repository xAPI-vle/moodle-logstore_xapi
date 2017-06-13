<?php namespace MXTranslator\Events;

class FacetofaceUnenrol extends FacetofaceEnrol {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override AttemtStarted
     */
    public function read(array $opts) {
        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'training_session_unenrol'
        ])];
    }
}