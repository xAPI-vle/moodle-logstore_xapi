<?php namespace logstore_emitter\xapi\recipes;
use \TinCan\Verb as tincan_verb;
use \logstore_emitter\xapi\agent as xapi_agent;
use \logstore_emitter\xapi\activity as xapi_activity;

class module_viewed extends base {
    /**
     * Constructs a new statement for course_module_viewed.
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
            'object' => new xapi_activity($opts['object'])
        ]);
    }
}
