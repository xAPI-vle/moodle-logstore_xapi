<?php

namespace src\transformer\repos;
defined('MOODLE_INTERNAL') || die();

use \stdClass as PhpObj;

require_once(__DIR__.'/Repository.php');

class TestRepository extends Repository {
    private $testdata;

    public function __construct($testdata) {
        $this->testdata = $testdata;
    }

    /**
     * Reads an array of objects from the store with the given type and query.
     * @param String $type
     * @param [String => Mixed] $query
     * @return PhpArr
     */
    public function read_records($type, array $query) {
        $records = $this->testdata->$type;
        $matchingrecords = [];

        foreach ($records as $record) {
            foreach ($query as $key => $value) {
                if ($record->$key === $value) {
                    $matchingrecords[] = (object) $record;
                }
            }
        }

        return $matchingrecords;
    }
}
