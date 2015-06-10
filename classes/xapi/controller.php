<?php namespace logstore_emitter\xapi;
use \stdClass as php_obj;

class controller extends php_obj {
    protected $service;
    public static $routes = [
        'course_viewed' => 'read_course_viewed_event',
        'module_viewed' => 'read_module_viewed_event',
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
        $route = $opts['recipe'];
        if (isset(static::$routes[$route])) {
            $event = $this->service->{static::$routes[$route]}($opts);
            return $this->service->create_event($event);
        } else {
            return null;
        }
    }
}
