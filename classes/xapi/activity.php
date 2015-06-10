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
                'name' => $this->read_activity_name($object),
                'description' => $this->read_activity_description($object),
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
        $name = isset($object->name) ? $object->name : null;
        if ($name === null) {
            return null;
        } else {
            return [
                $this->read_lang($object) => $name
            ];
        }
    }

    /**
     * Constructs a new activity description.
     * @param php_obj $object The moodle object to construct the activity description with.
     * @return string xAPI description
     */
    private function read_activity_description($object) {
        $description = isset($object->description) ? $object->description : null;
        if ($description === null) {
            return null;
        } else {
            return [
                $this->read_lang($object) => $description
            ];
        }
    }

    /**
     * Reads the language from the given object.
     * @param php_obj $object The moodle object to construct the activity name with.
     * @return string language
     */
    private function read_lang($object) {
        return isset($object->lang) ? $object->lang : 'en';
    }
}
