<?php namespace MXTranslator\Events;

class UserLoggedin extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        return array_merge(parent::read($opts), [
            'recipe' => 'user_loggedin',
            'app_url' => $opts['course']->url,
            'app_name' => $opts['course']->fullname ?: 'A Moodle course',
            'app_description' => $opts['course']->summary ?: 'A Moodle course',
            'app_ext' => $opts['course'],
            'app_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_course',
        ]);
    }
}