<?php namespace logstore_emitter\xapi\recipes;
use \TinCan\Verb as tincan_verb;
use \logstore_emitter\xapi\agent as xapi_agent;
use \logstore_emitter\xapi\activity as xapi_activity;

class submission_graded extends base {
    /**
     * Constructs a new statement for submission_graded.
     * @param [String => Mixed] $opts Options to construct the statement with.
     * @override base
     */
    public function __construct(array $opts) {
        parent::__construct([
            'actor' => new xapi_agent($opts['user']),
            'verb' => new tincan_verb([
                'id' => 'http://activitystrea.ms/schema/1.0/submit',
                'display' => [
                    'en-GB' => 'submitted',
                    'en-US' => 'submitted',
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
