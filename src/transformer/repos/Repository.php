<?php

namespace src\transformer\repos;
defined('MOODLE_INTERNAL') || die();

use \stdClass as PhpObj;

abstract class Repository extends PhpObj {

    /**
     * Reads an array of objects from the store with the given type and query.
     * @param String $type
     * @param [String => Mixed] $query
     * @return PhpArr
     */
    public abstract function read_records($type, array $query);

    /**
     * Reads an object from the store with the given type and query.
     * @param String $type
     * @param [String => Mixed] $query
     * @throws \Exception if the record was not found
     * @return PhpObj
     */
    public function read_record($type, array $query) {
        $records = $this->read_records($type, $query);
        $record = $records[0];
        if (!$record) {
            throw new \Exception("$type not found.");
        }
        return $record;
    }

    /**
     * Reads an object from the store with the given id.
     * @param String $id
     * @param String $type
     * @return PhpObj
     */
    public function read_record_by_id($type, $id) {
        return $this->read_record($type, ['id' => $id]);
    }
}
