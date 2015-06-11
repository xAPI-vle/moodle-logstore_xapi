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
            'course_name' => $opts['course']->fullname ?: 'A Moodle course',
            'course_description' => $opts['course']->summary ?: 'A Moodle course',
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
            'module_description' => $opts['module']->intro ?: 'A module',
            'module_ext' => $opts['module'],
            'module_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_module',
            'course_url' => $opts['course']->url,
            'course_name' => $opts['course']->fullname ?: 'A Moodle course',
            'course_description' => $opts['course']->summary ?: 'A Moodle course',
            'course_ext' => $opts['course'],
            'course_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_course',
        ]);
    }

    /**
     * Reads data for a attempt_started event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_attempt_started_event(array $opts) {
        return array_merge($this->read_event($opts), [
            'recipe' => 'attempt_started',
            'attempt_url' => $opts['attempt']->url,
            'attempt_ext' => $opts['attempt'],
            'attempt_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_attempt',
            'attempt_name' => $opts['attempt']->name,
            'module_url' => $opts['module']->url,
            'module_name' => $opts['module']->name,
            'module_description' => $opts['module']->intro ?: 'A module',
            'module_ext' => $opts['module'],
            'module_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_quiz',
            'course_url' => $opts['course']->url,
            'course_name' => $opts['course']->fullname ?: 'A Moodle course',
            'course_description' => $opts['course']->summary ?: 'A Moodle course',
            'course_ext' => $opts['course'],
            'course_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_course',
        ]);
    }

    /**
     * Reads data for a attempt_started event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_attempt_reviewed_event(array $opts) {
        $end = (new \DateTime)->setTimestamp($opts['attempt']->timestart);
        $start = (new \DateTime)->setTimestamp($opts['attempt']->timefinish);
        $duration = date_diff($start, $end)->format('P%YY%MM%DDT%HH%IM%SS');
        return array_merge($this->read_attempt_started_event($opts), [
            'recipe' => 'attempt_completed',
            'attempt_result' => (float) ($opts['attempt']->sumgrades ?: 0),
            'attempt_completed' => $opts['attempt']->state === 'finished',
            'attempt_duration' => $duration,
        ]);
    }

    /**
     * Reads data for a user_loggedin event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_user_loggedin_event(array $opts) {
        return array_merge($this->read_event($opts), [
            'recipe' => 'user_loggedin',
            'app_url' => $opts['course']->url,
            'app_name' => $opts['course']->fullname ?: 'A Moodle course',
            'app_description' => $opts['course']->summary ?: 'A Moodle course',
            'app_ext' => $opts['course'],
            'app_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_course',
        ]);
    }

    /**
     * Reads data for a user_loggedout event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_user_loggedout_event(array $opts) {
        return array_merge($this->read_event($opts), [
            'recipe' => 'user_loggedout',
            'app_url' => $opts['course']->url,
            'app_name' => $opts['course']->fullname ?: 'A Moodle course',
            'app_description' => $opts['course']->summary ?: 'A Moodle course',
            'app_ext' => $opts['course'],
            'app_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_course',
        ]);
    }
}
