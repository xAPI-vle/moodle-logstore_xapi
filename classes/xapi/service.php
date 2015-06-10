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
                    $opts['context_ext_key'] => $opts['context_ext'],
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
     * Reads data for a course from the opts.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    private function read_course(array $opts) {
        return [
            'id' => $opts['course_url'],
            'definition' => [
                'type' => 'http://adlnet.gov/expapi/activities/course',
                'name' => [
                    'en-GB' => $opts['course_name'],
                    'en-US' => $opts['course_name'],
                ],
                'description' => [
                    'en-GB' => $opts['course_description'] ?: 'A course',
                    'en-US' => $opts['course_description'] ?: 'A course',
                ],
                'extensions' => [
                    $opts['course_ext_key'] => $opts['course_ext']
                ],
            ],
        ];
    }

    /**
     * Reads data for a course_viewed event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_course_viewed_event(array $opts) {
        return array_merge($this->read_viewed_event($opts), [
            'object' => $this->read_course($opts),
        ]);
    }

    /**
     * Reads data for a module_viewed event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_module_viewed_event(array $opts) {
        return array_merge_recursive($this->read_viewed_event($opts), [
            'object' => [
                'id' => $opts['module_url'],
                'definition' => [
                    'type' => 'http://activitystrea.ms/schema/1.0/page',
                    'name' => [
                        'en-GB' => $opts['module_name'],
                        'en-US' => $opts['module_name'],
                    ],
                    'description' => [
                        'en-GB' => $opts['module_description'] ?: 'A module',
                        'en-US' => $opts['module_description'] ?: 'A module',
                    ],
                    'extensions' => [
                        $opts['module_ext_key'] => $opts['module_ext']
                    ],
                ],
            ],
            'context' => [
                'contextActivities' => [
                    'grouping' => [
                        $this->read_course($opts),
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
        $this->repo->create_event($statement);
        return $statement;
    }
}
