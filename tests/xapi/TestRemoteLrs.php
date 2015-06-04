<?php namespace Tests\Xapi;
use \TinCan\RemoteLRS as tincan_remote_lrs;
use \logstore_emitter\xapi\recipes\base as base_recipe;

class TestRemoteLrs extends tincan_remote_lrs {
    /**
     * Creates a statement in the LRS.
     * @param $statement
     * @return base_recipe
     * @override tincan_remote_lrs
     */
    public function saveStatement($statement) {
        return $statement;
    }
}
