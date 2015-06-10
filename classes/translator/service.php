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
        return array_merge($this->read_event($opts), [
            'recipe' => 'course_viewed',
            'course_url' => $opts['course']->url,
            'course_name' => $opts['course']->fullname,
            'course_description' => $opts['course']->summary,
            'course_ext' => $opts['course'],
            'course_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_course',
        ]);
    }

    /**
     * Reads data for a module_viewed event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_module_viewed_event(array $opts) {
        return array_merge($this->read_event($opts), [
            'recipe' => 'module_viewed',
            'module_url' => $opts['module']->url,
            'module_name' => $opts['module']->name,
            'module_description' => $opts['module']->intro,
            'module_ext' => $opts['module'],
            'module_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_module',
            'course_url' => $opts['course']->url,
            'course_name' => $opts['course']->fullname,
            'course_description' => $opts['course']->summary,
            'course_ext' => $opts['course'],
            'course_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_course',
        ]);
    }
}
