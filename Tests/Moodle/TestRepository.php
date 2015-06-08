<?php namespace Tests\Moodle;
use \logstore_emitter\moodle\repository as moodle_repository;
use \stdClass as php_obj;

class TestRepository extends moodle_repository {
    protected $store;

    /**
     * Constructs a new repository.
     * @param php_obj $store
     * @param php_obj $cfg
     */
    public function __construct(php_obj $store, php_obj $cfg) {
        parent::__construct($store, $cfg);
    }

    /**
     * Reads a user from the store with the given id.
     * @param [string => mixed] $opts
     * @return php_obj
     */
    public function read_object(array $opts) {
        return (object) [
            'id' => '123',
            'url' => 'http://www.example.com/object?id=123',
            'type' => 'course'
        ];
    }

    /**
     * Reads a course from the store with the given id.
     * @param string $id
     * @return php_obj
     */
    public function read_course($id) {
        return (object) [
            'id' => $id,
            'url' => 'http://www.example.com/course?id=' . $id,
            'type' => 'course'
        ];
    }

    /**
     * Reads a user from the store with the given id.
     * @param string $id
     * @return php_obj
     */
    public function read_user($id) {
        return (object) [
            'id' => $id,
            'name' => 'Bob',
            'url' => 'http://www.example.com/user/profile.php?id=' . $id,
            'type' => 'user'
        ];
    }
}
