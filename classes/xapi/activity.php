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
                'type' => $this->read_activity_type(isset($object->type) ? $object->type : null),
                'name' => $this->read_activity_name($object)
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
        return isset($types[$type]) ? $types[$type] : 'http://lrs.learninglocker.net/define/type/unknown';
    }

    /**
     * Constructs a new activity name.
     * @param php_obj $object The moodle object to construct the activity name with.
     * @return string xAPI name
     */
    private function read_activity_name($object) {
        $name = isset($object->fullname) ? $object->fullname : (isset($object->name) ? $object->name : null);
        if ($name === null) {
            return null;
        } else {
            return [
                'en-GB' => $name,
                'en-US' => $name
            ];
        }
    }
}
