<?php namespace logstore_emitter\xapi;
use \logstore_emitter\xapi\recipes\base as base_recipe;
use \stdClass as php_obj;

class service extends php_obj {
    public static $action_to_recipe = [
        '\core\event\course_viewed' => 'course_viewed'
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
