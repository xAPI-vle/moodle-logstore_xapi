<?php namespace Tests;
use \XREmitter\Repository as EmitterRepository;

class TestRepository extends EmitterRepository {
    /**
     * Creates an event in the store.
     * @param [string => mixed] $event
     * @return [string => mixed]
     */
    public function create_event(array $event) {
        return $event;
    }
}
