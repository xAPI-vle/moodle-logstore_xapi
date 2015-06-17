<?php namespace XREmitter\Events;
use \XREmitter\Repository as Repository;
use \stdClass as PhpObj;

abstract class Event extends PhpObj {
    protected $repo;

    /**
     * Constructs a new Event.
     * @param repository $repo
     */
    public function __construct(Repository $repo) {
        $this->repo = $repo;
    }

    /**
     * Creates an event in the repository.
     * @param [string => mixed] $event
     * @return [string => mixed]
     */
    public function create(array $event) {
        return $this->repo->createEvent($event);
    }

    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     */
    public function read(array $opts) {
        return [
            'actor' => [
                'name' => $opts['user_name'],
                'account' => [
                    'homePage' => $opts['user_url'],
                    'name' => $opts['user_id'],
                ],
            ],
            'context' => [
                'platform' => $opts['context_platform'],
                'language' => $opts['context_lang'],
                'extensions' => [
                    $opts['context_ext_key'] => $opts['context_ext'],
                ],
            ],
            'timestamp' => $opts['time'],
        ];
    }

    protected function readActivity(array $opts, $key, $type) {
        return [
            'id' => $opts[$key.'_url'],
            'definition' => [
                'type' => $type,
                'name' => [
                    'en-GB' => $opts[$key.'_name'],
                    'en-US' => $opts[$key.'_name'],
                ],
                'description' => [
                    'en-GB' => $opts[$key.'_description'],
                    'en-US' => $opts[$key.'_description'],
                ],
                'extensions' => [
                    $opts[$key.'_ext_key'] => $opts[$key.'_ext']
                ],
            ],
        ];
    }

    protected function readCourse($opts) {
        return $this->readActivity($opts, 'course', 'http://adlnet.gov/expapi/activities/course');
    }

    protected function readApp($opts) {
        return $this->readActivity($opts, 'app', 'http://activitystrea.ms/schema/1.0/application');
    }

    protected function readModule($opts) {
        return $this->readActivity($opts, 'module', 'http://adlnet.gov/expapi/activities/module');
    }
}