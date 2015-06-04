<?php namespace logstore_emitter\xapi;
use \stdClass as php_obj;

class repository extends php_obj {
    const VERSION = '1.0.1';
    protected $store;

    /**
     * Constructs a new repository.
     * @param php_obj $store
     */
    public function __construct(php_obj $store) {
        $this->store = $store;
    }

    /**
     * Reads a user from the store with the given id.
     * @param string $user_id
     * @return php_obj
     */
    public function read_user($user_id) {
        return $this->store->get_record('user', ['id' => $user_id]);
    }
}
