<?php namespace logstore_emitter\moodle;
use \stdClass as php_obj;

class service extends php_obj {
    protected $repo;

    /**
     * Constructs a new service.
     * @param repository $repo The LRS to be used to store statements.
     */
    public function __construct(repository $repo) {
        $this->repo = $repo;
    }

    /**
     * Reads data for an event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    private function read_event(array $opts) {
        $opts['url'] = $this->repo->read_event_url($opts);
        return [
            'user' => $this->repo->read_user($opts['userid']),
            'course' => $this->repo->read_course($opts['courseid']),
            'event' => $opts,
        ];
    }

    /**
     * Reads data for a course_viewed event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_course_viewed_event(array $opts) {
        return $this->read_event($opts);
    }
}
