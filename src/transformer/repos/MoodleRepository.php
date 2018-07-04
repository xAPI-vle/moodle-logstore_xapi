<?php

namespace src\transformer\repos;
defined('MOODLE_INTERNAL') || die();

require_once(__DIR__.'/Repository.php');

class MoodleRepository extends Repository {
    private $store;

    /**
     * Constructs a new Repository.
     * @param $store
     */
    public function __construct($store) {
        $this->store = $store;
    }

    /**
     * Reads an array of objects from the store with the given type and query.
     * @param String $type
     * @param [String => Mixed] $query
     * @return PhpArr
     */
    public function read_records($type, array $query) {
        return $this->store->get_records($type, $query);
    }

    /**
     * Reads an object from the store with the given type and query.
     * @param String $type
     * @param [String => Mixed] $query
     * @throws \Exception if the record was not found
     * @return PhpObj
     */
    public function read_record($type, array $query) {
        $record = $this->store->get_record($type, $query);
        if ($record === false) {
            throw new \Exception("$type not found.");
        }
        return $record;
    }
}
