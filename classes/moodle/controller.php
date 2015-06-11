<?php namespace logstore_emitter\moodle;
use \stdClass as php_obj;

class controller extends php_obj {
    protected $service;
    public static $routes = [
        '\core\event\course_viewed' => 'read_course_viewed_event',
        '\mod_page\event\course_module_viewed' => 'read_module_viewed_event',
        '\mod_quiz\event\course_module_viewed' => 'read_module_viewed_event',
        '\mod_url\event\course_module_viewed' => 'read_module_viewed_event',
        '\mod_folder\event\course_module_viewed' => 'read_module_viewed_event',
        '\mod_book\event\course_module_viewed' => 'read_module_viewed_event',
        '\mod_quiz\event\attempt_preview_started' => 'read_attempt_started_event',
        '\mod_quiz\event\attempt_reviewed' => 'read_attempt_started_event',
        '\core\event\user_loggedin' => 'read_user_loggedin_event',
        '\core\event\user_loggedout' => 'read_user_loggedout_event',
        '\mod_assign\event\submission_graded' => 'read_assignment_graded_event',
        '\mod_assign\event\assessable_submitted' => 'read_assignment_submitted_event',
    ];

    /**
     * Constructs a new controller.
     * @param service $service
     */
    public function __construct(service $service) {
        $this->service = $service;
    }

    /**
     * Creates a new event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function create(array $opts) {
        $route = $opts['eventname'];
        if (isset(static::$routes[$route])) {
            return $this->service->{static::$routes[$route]}($opts);
        } else {
            return null;
        }
    }
}
