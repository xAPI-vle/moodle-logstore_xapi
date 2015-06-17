<?php namespace MXTranslator\Events;

class UserLoggedout extends UserLoggedin {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override UserLoggedin
     */
    public function read(array $opts) {
        return array_merge(parent::read($opts), [
            'recipe' => 'user_loggedout',
        ]);
    }
}