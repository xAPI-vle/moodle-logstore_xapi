<?php namespace logstore_emitter\xapi;
use \stdClass as php_obj;

class controller extends php_obj {
    protected $service;
    public static $routes = [
        'course_viewed' => 'read_course_viewed_event',
        'module_viewed' => 'read_module_viewed_event',
        'attempt_started' => 'read_attempt_started_event',
        'attempt_completed' => 'read_attempt_completed_event',
        'user_loggedin' => 'read_user_loggedin_event',
        'user_loggedout' => 'read_user_loggedout_event',
        'assignment_graded' => 'read_assignment_graded_event',
        'assignment_submitted' => 'read_assignment_submitted_event',
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
    public function create_event(array $opts) {
        $route = isset($opts['recipe']) ? $opts['recipe'] : '';
        \logstore_emitter\logger::log($route);
        if (isset(static::$routes[$route])) {
            $event = $this->service->{static::$routes[$route]}($opts);
            return $this->service->create_event($event);
        } else {
            return null;
        }
    }
}
