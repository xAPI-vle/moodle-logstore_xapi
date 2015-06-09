<?php namespace logstore_emitter\xapi;
use \logstore_emitter\xapi\recipes\base as base_recipe;
use \stdClass as php_obj;

class service extends php_obj {
    public static $action_to_recipe = [
        '\mod_page\event\course_module_viewed' => 'module_viewed',
        '\core\event\course_completed' => 'course_completed',
        '\core\event\course_module_completion_updated' => 'module_completed',
        '\mod_data\event\comment_created' => 'comment_created',
        '\mod_quiz\event\attempt_started' => 'quiz_started',
        '\mod_quiz\event\attempt_submitted' => 'quiz_submitted',
        '\mod_quiz\event\attempt_abandoned' => 'quiz_abandoned',
        '\core\event\user_loggedin' => 'user_loggedin',
        '\core\event\user_loggedout' => 'user_loggedout',
        '\mod_assign\event\submission_graded' => 'submission_graded'
    ];

    /**
     * Constructs a new service.
     * @param repository $repo The LRS to be used to store statements.
     */
    public function __construct(repository $repo) {
        $this->repo = $repo;
    }

    /**
     * Creates a new statement.
     * @param [string => mixed] $opts
     * @return base_recipe
     */
    public function create(array $opts) {
        $eventname = $opts['eventname'];
        if (isset(static::$action_to_recipe[$eventname])) {
            $recipe = '\logstore_emitter\xapi\recipes\\'.static::$action_to_recipe[$opts['eventname']];
            $statement = new $recipe($opts);
            $this->repo->create_statement($statement);
            return $statement;
        } else {
            return null;
        }
    }
}
