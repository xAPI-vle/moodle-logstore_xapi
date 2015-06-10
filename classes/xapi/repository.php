<?php namespace logstore_emitter\xapi;
use \TinCan\Statement as tincan_statement;
use \TinCan\RemoteLRS as tincan_remote_lrs;
use \stdClass as php_obj;

class repository extends php_obj {
    /**
     * Constructs a new repository.
     * @param tincan_remote_lrs $store
     */
    public function __construct(tincan_remote_lrs $store) {
        $this->store = $store;
    }

    /**
     * Creates a statement in the store.
     * @param tincan_statement $statement
     * @return tincan_statement
     */
    public function create_statement(tincan_statement $statement) {
        $this->store->saveStatement($statement);
        return $statement;
    }
}
