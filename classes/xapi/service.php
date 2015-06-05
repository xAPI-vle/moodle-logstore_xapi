<?php namespace logstore_emitter\xapi;
use \logstore_emitter\xapi\recipes\base as base_recipe;
use \stdClass as php_obj;

class service extends php_obj {
    protected static $action_to_recipe = [
        '\mod_scorm\event\course_module_viewed' => 'module_viewed',
        '\core\event\course_completed' => 'course_completed'
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
        $recipe = '\logstore_emitter\xapi\recipes\\'.static::$action_to_recipe[$opts['eventname']];
        $statement = new $recipe($opts);
        $this->repo->create_statement($statement);
        return $statement;
    }
}
