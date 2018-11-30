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
     * @throws \Exception
     */
    public function read_record_by_id($type, $id) {
        return $this->read_record($type, ['id' => $id]);
    }
}
