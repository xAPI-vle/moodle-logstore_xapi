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
     * Reads data for a app from the opts.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    private function read_app(array $opts) {
        return [
            'id' => $opts['app_url'],
            'definition' => [
                'type' => 'http://activitystrea.ms/schema/1.0/application',
                'name' => [
                    'en-GB' => $opts['app_name'],
                    'en-US' => $opts['app_name'],
                ],
                'description' => [
                    'en-GB' => $opts['app_description'],
                    'en-US' => $opts['app_description'],
                ],
                'extensions' => [
                    $opts['app_ext_key'] => $opts['app_ext']
                ],
            ],
        ];
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
                    'en-GB' => $opts['course_description'],
                    'en-US' => $opts['course_description'],
                ],
                'extensions' => [
                    $opts['course_ext_key'] => $opts['course_ext']
                ],
            ],
        ];
    }

    /**
     * Reads data for a module from the opts.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    private function read_module(array $opts) {
        return [
            'id' => $opts['module_url'],
            'definition' => [
                'type' => 'http://adlnet.gov/expapi/activities/module',
                'name' => [
                    'en-GB' => $opts['module_name'],
                    'en-US' => $opts['module_name'],
                ],
                'description' => [
                    'en-GB' => $opts['module_description'],
                    'en-US' => $opts['module_description'],
                ],
                'extensions' => [
                    $opts['module_ext_key'] => $opts['module_ext']
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
            'object' => $this->read_module($opts),
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
     * Reads data for a attempt_started event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_attempt_started_event(array $opts) {
        return array_merge_recursive($this->read_event($opts), [
            'verb' => [
                'id' => 'http://activitystrea.ms/schema/1.0/start',
                'display' => [
                    'en-GB' => 'started',
                    'en-US' => 'started',
                ],
            ],
            'object' => [
                'id' => $opts['attempt_url'],
                'definition' => [
                    'type' => 'http://activitystrea.ms/schema/1.0/page',
                    'name' => [
                        'en-GB' => $opts['attempt_name'],
                        'en-US' => $opts['attempt_name'],
                    ],
                    'extensions' => [
                        $opts['attempt_ext_key'] => $opts['attempt_ext']
                    ],
                ],
            ],
            'context' => [
                'contextActivities' => [
                    'grouping' => [
                        $this->read_course($opts),
                        $this->read_module($opts),
                    ],
                ],
            ],
        ]);
    }

    /**
     * Reads data for a attempt_completed event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_attempt_completed_event(array $opts) {
        return array_merge($this->read_attempt_started_event($opts), [
            'verb' => [
                'id' => 'http://adlnet.gov/expapi/verbs/completed',
                'display' => [
                    'en-GB' => 'completed',
                    'en-US' => 'completed',
                ],
            ],
            'result' => [
                'score' => [
                    'raw' => $opts['attempt_result'],
                ],
                'completion' => $opts['attempt_completed'],
                'duration' => $opts['attempt_duration'],
            ],
        ]);
    }

    /**
     * Reads data for a user_loggedin event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_user_loggedin_event(array $opts) {
        return array_merge($this->read_event($opts), [
            'verb' => [
                'id' => 'https://brindlewaye.com/xAPITerms/verbs/loggedin/',
                'display' => [
                    'en-GB' => 'logged in to',
                    'en-US' => 'logged in to',
                ],
            ],
            'object' => $this->read_app($opts),
        ]);
    }

    /**
     * Reads data for a user_loggedout event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_user_loggedout_event(array $opts) {
        return array_merge($this->read_event($opts), [
            'verb' => [
                'id' => 'https://brindlewaye.com/xAPITerms/verbs/loggedout/',
                'display' => [
                    'en-GB' => 'logged out of',
                    'en-US' => 'logged out of',
                ],
            ],
            'object' => $this->read_app($opts),
        ]);
    }

    /**
     * Reads data for a assignment_graded event.
     * @param [string => mixed] $opts
     * @return [string => mixed]
     */
    public function read_assignment_graded_event(array $opts) {
        return array_merge($this->read_event($opts), [
            'verb' => [
                'id' => 'http://adlnet.gov/expapi/verbs/completed',
                'display' => [
                    'en-GB' => 'completed',
                    'en-US' => 'completed',
                ],
            ],
            'result' => [
                'score' => [
                    'raw' => $opts['grade_result'],
                ],
                'completion' => true,
            ],
            'object' => $this->read_module($opts),
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
