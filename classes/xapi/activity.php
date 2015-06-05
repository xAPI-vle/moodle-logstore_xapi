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
            'id' => $object->url,
            'definition' => [
                'type' => $this->read_activity_type($object->type)
            ]
        ]);
    }

    /**
     * Reads the activity type from the given moodle activity type.
     * @param string $type Moodle type
     * @return string xAPI type
     */
    private function read_activity_type($type) {
        $types = [
            'course' => 'http://adlnet.gov/expapi/activities/course',
            'course_module' => 'http://adlnet.gov/expapi/activities/module'
        ];
        return $types[$type];
    }
}
