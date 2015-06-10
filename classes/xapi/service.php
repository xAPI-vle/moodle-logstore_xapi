<?php namespace logstore_emitter\xapi;
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
                    $opts['context_ext_key'] => $opts['event'],
                ],
            ],
            'timestamp' => $opts['time'],
        ];
    }

    /**
     * Reads data for a viwed event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    private function read_viewed_event(array $opts) {
        return array_merge($this->read_event($opts), [
            'verb' => [
                'id' => 'http://id.tincanapi.com/verb/viewed',
                'display' => [
                    'en-GB' => 'viewed',
                    'en-US' => 'viewed',
                ],
            ],
        ]);
    }

    /**
     * Reads data for a course_viewed event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_course_viewed_event(array $opts) {
        $data = array_merge($this->read_event($opts), [
            'object' => [
                'id' => $opts['object_url'],
                'definition' => [
                    'type' => 'http://adlnet.gov/expapi/activities/course',
                    'name' => [
                        'en-GB' => $opts['obj_name'],
                        'en-US' => $opts['obj_name'],
                    ],
                    'description' => [
                        'en-GB' => $opts['obj_description'],
                        'en-US' => $opts['obj_description'],
                    ],
                    'extensions' => [
                        $opts['obj_ext_key'] => $opts['obj_ext']
                    ],
                ],
            ],
        ]);
    }

    /**
     * Creates a new event in the repository.
     * @param [string => mixed] $statement
     * @return [string => mixed]
     */
    public function create_event(array $statement) {
        $this->repo->create_statement($statement);
        return $statement;
    }
}
