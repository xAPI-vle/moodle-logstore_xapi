<?php namespace MXTranslator\Events;
use \MXTranslator\Repository as Repository;
use \stdClass as PhpObj;

class Event extends PhpObj {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     */
    public function read(array $opts) {
        return [
            'user_id' => $opts['user']->id,
            'user_url' => $opts['user']->url,
            'user_name' => $opts['user']->username,
            'context_lang' => $opts['course']->lang,
            'context_platform' => 'Moodle',
            'context_ext' => $opts['event'],
            'context_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_logstore_standard_log',
            'time' => date('c', $opts['event']['timecreated']),
        ];
    }
}