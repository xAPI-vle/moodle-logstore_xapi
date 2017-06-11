<?php namespace XREmitter\Tests;
use \XREmitter\Repository as EmitterRepository;

class TestRepository extends EmitterRepository {

    /**
     * Creates events in the store.
     * @param [string => mixed] $event
     * @return [string => mixed]
     */
    public function createEvents(array $events) {
        return $events;
    }
}
