<?php namespace logstore_emitter\xapi;
use \TinCan\RemoteLRS as tincan_remote_lrs;

class store extends tincan_remote_lrs {
    const VERSION = '1.0.1';

    /**
     * Constructs a new store.
     * @param string $endpoint IRI for the LRS (i.e. 'http://lrs.learninglocker.net/data/xAPI/').
     * @param string $username Basic auth username.
     * @param string $password Basic auth password.
     * @override tincan_remote_lrs
     */
    public function __construct($endpoint, $username, $password) {
        parent::construct($endpoint, static::VERSION, $username, $password);
    }
}
