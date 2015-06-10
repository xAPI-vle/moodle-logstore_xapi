<?php namespace logstore_emitter\xapi\recipes;
use \TinCan\Verb as tincan_verb;
use \logstore_emitter\xapi\agent as xapi_agent;
use \logstore_emitter\xapi\activity as xapi_activity;
use \logstore_emitter\xapi\activity as xapi_context;

class course_viewed extends base {
    /**
     * Constructs a new statement for course_viewed.
     * @param [String => Mixed] $opts Options to construct the statement with.
     * @override base
     */
    public function __construct(array $opts) {
        parent::__construct([
            'actor' => new xapi_agent($opts['user']),
            'verb' => new tincan_verb([
                'id' => 'http://id.tincanapi.com/verb/viewed',
                'display' => [
                    'en-GB' => 'viewed',
                    'en-US' => 'viewed',
                ]
            ]),
            'object' => new xapi_activity($opts['object']),
            'context' => [
                'platform' => 'Moodle',
                'language' => $this->read_lang($event),
                'extensions' => [
                    'http://lrs.learninglocker.net/define/extensions/moodle_logstore_standard_log' => $event
                ]
            ]
        ]);
    }
}
