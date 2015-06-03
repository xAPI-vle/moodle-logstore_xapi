<?php namespace logstore_emitter\xapi;
use \TinCan\Activity as tincan_activity;
use \logstore_emitter\moodle\object as moodle_object;

class activity extends tincan_activity {
    /**
     * Constructs a new activity.
     * @param moodle_object $object The moodle object to construct the activity with.
     * @override tincan_activity
     */
    public function __construct(moodle_object $object) {
        parent::construct([
            'id' => $object->url
        ]);
    }
}
