<?php namespace logstore_emitter\translator;
use \stdClass as php_obj;

class service extends php_obj {
    /**
     * Constructs a new service.
     */
    public function __construct() {}

    /**
     * Reads data for an event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    private function read_event(array $opts) {
        return [
            'user_id' => $opts['user']->id,
            'user_url' => $opts['user']->url,
            'user_name' => $opts['user']->username,
            'obj_url' => $opts['course']->url,
            'obj_name' => $opts['course']->fullname,
            'obj_description' => $opts['course']->summary,
            'obj_ext' => $opts['course'],
            'object_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_course',
            'context_lang' => $opts['course']->lang,
            'context_platform' => 'Moodle',
            'context_ext' => $opts['event'],
            'context_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_logstore_standard_log',
            'time' => date('c', $opts['event']['timecreated']),
        ];
    }

    /**
     * Reads data for a course_viewed event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_course_viewed_event(array $opts) {
        $data = array_merge($this->read_event($opts), [
            'recipe' => 'course_viewed'
        ]);
    }
}
