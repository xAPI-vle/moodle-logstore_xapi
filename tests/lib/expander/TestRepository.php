<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace LogExpander\Tests;

defined('MOODLE_INTERNAL') || die();

use \LogExpander\Repository as MoodleRepository;
use \stdClass as PhpObj;

class TestRepository extends MoodleRepository {

    protected $fakemoodledatabase;

    public function __construct($store, PhpObj $cfg) {
        parent::__construct($store, $cfg);
        $file = file_get_contents(__DIR__ . "/fakeDB.json");
        $this->fakemoodledatabase = json_decode($file, true);
    }

    /**
     * Reads an object from the store with the given id.
     * @param string $type
     * @param [string => mixed] $query
     * @return php_obj
     * @override MoodleRepository
     */
    protected function read_store_record($type, array $query, $index = 0) {
        $records = $this->read_store_records($type, $query);
        if (is_array($records)) {
            return reset($records);
        }
        return $records;
    }

    /**
     * Reads an array of objects from the store with the given type and query.
     * @param String $type
     * @param [String => Mixed] $query
     * @return PhpArr
     * @override MoodleRepository
     */
    protected function read_store_records($type, array $query) {
        $records = $this->fakemoodledatabase[$type];
        $matchingrecords = [];

        foreach ($records as $record) {
            foreach ($query as $key => $value) {
                if ($record[$key] === $value) {
                    // Required for assertRecord in EventTest.php to pass, but what's the purpose of including and testing this?
                    $record['type'] = 'object';
                    $matchingrecords[$record['id']] = (object) $record;
                }
            }
        }

        // If no matching records found, try to create some!
        if (count($matchingrecords) == 0) {
            foreach ($records as $record) {
                $record['type'] = 'object';
                $id = $record['id'];
                foreach ($query as $key => $value) {
                    $record[$key] = $value;
                }
                $matchingrecords[$id] = (object) $record;
            }
        }

        // Always return at least 2 records.
        if (count($matchingrecords) == 1) {
            $newrecord = clone(reset($matchingrecords));
            $newid = strval(intval($newrecord->id) + 1);
            $newrecord->id = $newid;
            $matchingrecords[$newid] = $newrecord;
        }

        return $matchingrecords;
    }

    protected function fullname($user) {
        return "test_fullname";
    }

    /**
     * Reads an object from the store with the given id.
     * @param string $id
     * @param string $type
     * @return php_obj
     */
    public function read_object($id, $type) {
        $model = $this->read_store_record($type, ['id' => $id]);
        $model->id = $id;
        return $model;
    }
}
