<?php namespace LogExpander\Events;
use \LogExpander\Repository as Repository;
use \stdClass as PhpObj;

class Event extends PhpObj {
    protected $repo;

    /**
     * Constructs a new Event.
     * @param repository $repo
     */
    public function __construct(Repository $repo) {
        $this->repo = $repo;
    }

    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     */
    public function read(array $opts) {
        return [
            'user' => $this->repo->readUser($opts['userid']),
            'course' => $this->repo->readCourse($opts['courseid']),
            'event' => $opts,
        ];
    }
}