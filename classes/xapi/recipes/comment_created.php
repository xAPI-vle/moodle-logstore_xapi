<?php namespace logstore_emitter\xapi\recipes;
use \TinCan\Verb as tincan_verb;
use \logstore_emitter\xapi\agent as xapi_agent;
use \logstore_emitter\xapi\activity as xapi_activity;

class comment_created extends base {
    /**
     * Constructs a new statement for comment_created.
     * @param [String => Mixed] $opts Options to construct the statement with.
     * @override base
     */
    public function __construct(array $opts) {
        parent::__construct([
            'actor' => new xapi_agent($opts['user']),
            'verb' => new tincan_verb([
                'id' => 'http://adlnet.gov/expapi/verbs/commented',
                'display' => [
                    'en-GB' => 'commented',
                    'en-US' => 'commented',
                ]
            ]),
            'object' => new xapi_activity($opts['object']),
            'context' => [
                'contextActivities' => [
                    'grouping' => [
                        new xapi_activity($opts['course'])
                    ]
                ]
            ]
        ]);
    }
}
