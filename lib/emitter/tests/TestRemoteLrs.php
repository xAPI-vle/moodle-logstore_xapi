<?php namespace XREmitter\Tests;
use \TinCan\RemoteLRS as TinCanRemoteLrs;

class TestRemoteLrs extends TinCanRemoteLrs {
    /**
     * Creates a statement in the LRS.
     * @param $statement
     * @return \TinCan\Statement
     * @override TinCanRemoteLrs
     */
    public function saveStatement($statement) {
        return (object) [
            'success' => true,
            'response' => $statement,
        ];
    }
}
