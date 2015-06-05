<?php namespace logstore_emitter\moodle;
use \core\event\base as base_event;
use \stdClass as php_obj;

class service extends php_obj {
    /**
     * Constructs a new service.
     * @param repository $repo The LRS to be used to store statements.
     */
    public function __construct(repository $repo) {
        $this->repo = $repo;
    }

    /**
     * Creates a new event.
     * @param [string => mixed] $opts
     * @return [string => mixed] Event
     */
    public function create(array $opts) {
        $opts['user'] = $this->repo->read_user($opts['userid']);
        $opts['course'] = $this->repo->read_course($opts['courseid']);
        $opts['object'] = $this->repo->read_object($opts);
        return $opts;
    }
}
