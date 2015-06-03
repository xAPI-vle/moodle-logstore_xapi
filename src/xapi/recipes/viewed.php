<?php namespace logstore_emitter\xapi\recipes;
use \TinCan\Verb as tincan_verb;
use \logstore_emitter\xapi\agent as agent;
use \logstore_emitter\xapi\object as object;

class viewed extends base {
    /**
     * Constructs a new statement for viewed.
     * @param [String => Mixed] $opts Options to construct the statement with.
     * @override base
     */
    public function __construct(array $opts) {
        parent::construct([
            'actor' => new agent($opts['user']),
            'verb' => new tin_can_verb([
                'id' => 'http://id.tincanapi.com/verb/viewed',
                'display' => [
                    'en-GB' => 'viewed',
                    'en-US' => 'viewed',
                ]
            ]),
            'object' => new object($opts['object'])
        ]);
    }
}
