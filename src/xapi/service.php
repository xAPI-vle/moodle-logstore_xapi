<?php namespace logstore_emitter\xapi;
use \TinCan\RemoteLRS as tincan_remote_lrs;
use \logstore_emitter\xapi\recipes\base as base_recipe;
use \stdClass as php_obj;

class service extends php_obj {
    protected static $action_to_recipe = [
        '\mod_scorm\event\course_module_viewed' => 'viewed'
    ];

    /**
     * Constructs a new service.
     * @param tincan_remote_lrs $store The LRS to be used to store statements.
     */
    public function __construct(tincan_remote_lrs $store) {
        $this->store = $store;
    }

    /**
     * Creates a new statement.
     * @param [string => mixed] $opts
     * @return base_recipe
     */
    public function create(array $opts) {
        $recipe = '\logstore_emitter\xapi\recipes\\'.static::$action_to_recipe[$opts['eventname']];
        $statement = new $recipe($opts);
        $this->store->saveStatement($statement);
        return $statement;
    }
}
