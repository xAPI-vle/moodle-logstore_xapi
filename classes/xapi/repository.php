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
     * Creates an event in the store.
     * @param [string => mixed] $event
     * @return [string => mixed]
     */
    public function create_event(array $event) {
        $response = $this->store->saveStatement(new tincan_statement($event));
        var_dump($response);
        return $event;
    }
}
