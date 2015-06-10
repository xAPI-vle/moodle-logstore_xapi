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
