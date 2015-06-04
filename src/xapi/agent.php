<?php namespace logstore_emitter\xapi;
use \TinCan\Agent as tincan_agent;
use \stdClass as php_obj;

class agent extends tincan_agent {
    /**
     * Constructs a new agent.
     * @param php_obj $user The moodle user to construct the agent with.
     * @override tincan_agent
     */
    public function __construct(php_obj $user) {
        parent::__construct([
            'name' => $user->name,
            'account' => [
                'name' => $user->id,
                'homePage' => $user->url
            ]
        ]);
    }
}
