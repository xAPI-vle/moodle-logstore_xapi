<?php namespace Tests\Xapi;
use \TinCan\RemoteLRS as tincan_remote_lrs;
use \TinCan\Statement as tincan_statement;

class TestRemoteLrs extends tincan_remote_lrs {
    /**
     * Creates a statement in the LRS.
     * @param $statement
     * @return tincan_statement
     * @override tincan_remote_lrs
     */
    public function saveStatement($statement) {
        return (object) [
            'success' => true,
            'response' => $statement,
        ];
    }
}
