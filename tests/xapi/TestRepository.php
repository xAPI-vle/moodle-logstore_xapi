<?php namespace Tests\Xapi;
use \logstore_emitter\xapi\repository as xapi_repository;

class TestRepository extends xapi_repository {
    /**
     * Constructs a new repository.
     * @param string $endpoint IRI for the LRS (i.e. 'http://lrs.learninglocker.net/data/xAPI/').
     * @param string $username Basic auth username.
     * @param string $password Basic auth password.
     * @override xapi_repository
     */
    public function __construct($endpoint, $username, $password) {
        parent::__construct($endpoint, $username, $password);
    }

    /**
     * Pretends to save a statement.
     * @param mixed $statement
     * @return mixed Returns the given statement for testing.
     * @override xapi_repository
     */
    public function saveStatement($statement) {
        return $statement;
    }
}
