<?php namespace XREmitter;
use \TinCan\RemoteLRS as TinCanRemoteLrs;
use \TinCan\Statement as TinCanStatement;
use \stdClass as PhpObj;

class Repository extends PhpObj {
    protected $store;

    /**
     * Constructs a new Repository.
     * @param TinCanRemoteLrs $store
     * @param PhpObj $cfg
     */
    public function __construct(TinCanRemoteLrs $store) {
        $this->store = $store;
    }

    /**
     * Creates an event in the store.
     * @param [string => mixed] $statements
     * @return [string => mixed]
     */
    public function createEvents(array $statements) {
        $response = $this->store->saveStatements($statements);
        return [
            "statements" => $statements,
            "response" => $response
        ];
    }
}
