<?php namespace logstore_emitter\xapi;
use \TinCan\Activity as tincan_activity;
use \stdClass as php_obj;

class activity extends tincan_activity {
    /**
     * Constructs a new activity.
     * @param php_obj $object The moodle object to construct the activity with.
     * @override tincan_activity
     */
    public function __construct(php_obj $object) {
        parent::__construct([
            'id' => $object->url
        ]);
    }
}
