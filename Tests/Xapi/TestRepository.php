<?php namespace Tests\Xapi;
use \logstore_emitter\xapi\repository as xapi_repository;

class TestRepository extends xapi_repository {
    /**
     * Creates an event in the store.
     * @param [string => mixed] $event
     * @return [string => mixed]
     * @override xapi_repository
     */
    public function create_event(array $event) {
        return $event;
    }
}
