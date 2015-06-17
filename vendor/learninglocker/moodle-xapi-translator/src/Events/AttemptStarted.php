<?php namespace MXTranslator\Events;

class AttemptStarted extends ModuleViewed {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override ModuleViewed
     */
    public function read(array $opts) {
        return array_merge(parent::read($opts), [
            'recipe' => 'attempt_started',
            'attempt_url' => $opts['attempt']->url,
            'attempt_ext' => $opts['attempt'],
            'attempt_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_attempt',
            'attempt_name' => $opts['attempt']->name,
        ]);
    }
}