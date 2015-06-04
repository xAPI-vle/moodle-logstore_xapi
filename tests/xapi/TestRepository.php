<?php namespace Tests\Xapi;
use \logstore_emitter\xapi\repository as xapi_repository;
use \logstore_emitter\xapi\recipes\base as base_recipe;
use \TinCan\RemoteLRS as tincan_remote_lrs;

class TestRepository extends xapi_repository {
    /**
     * Constructs a new repository.
     * @param tincan_remote_lrs $store
     * @override xapi_repository
     */
    public function __construct(tincan_remote_lrs $store) {
        parent::__construct($store);
    }

    /**
     * Creates a statement in the store.
     * @param base_recipe $statement
     * @return base_recipe
     * @override xapi_repository
     */
    public function create_statement(base_recipe $statement) {
        return $statement;
    }
}
