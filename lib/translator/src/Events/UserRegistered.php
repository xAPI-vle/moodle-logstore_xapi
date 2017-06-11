<?php namespace MXTranslator\Events;

class UserRegistered extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'user_registered',
            'user_id' => $opts['relateduser']->id,
            'user_url' => $opts['relateduser']->url,
            'user_name' => $opts['relateduser']->fullname,
        ])];
    }
}