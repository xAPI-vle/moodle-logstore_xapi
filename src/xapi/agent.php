<?php namespace logstore_emitter\xapi;
use \TinCan\Agent as tincan_agent;
use \logstore_emitter\moodle\user as moodle_user;

class agent extends tincan_agent {
    /**
     * Constructs a new agent.
     * @param moodle_user $user The moodle user to construct the agent with.
     * @override tincan_agent
     */
    public function __construct(moodle_user $user) {
        parent::construct([
            'name' => $user->name,
            'account' => [
                'name' => $user->id,
                'homePage' => $user->url
            ]
        ]);
    }
}
