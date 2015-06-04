<?php namespace logstore_emitter\xapi;
use \logstore_emitter\xapi\recipes\base as base_recipe;
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
     * @param base_recipe $statement
     * @return base_recipe
     */
    public function create_statement(base_recipe $statement) {
        $this->store->saveStatement($statement);
        return $statement;
    }
}
