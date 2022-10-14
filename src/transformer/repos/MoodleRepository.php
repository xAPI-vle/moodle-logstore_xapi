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

require_once(__DIR__.'/Repository.php');

/**
 * Moodle repository.
 *
 * @package   logstore_xapi
 * @copyright Jerret Fowler <jerrett.fowler@gmail.com>
 *            Ryan Smith <https://www.linkedin.com/in/ryan-smith-uk/>
 *            David Pesce <david.pesce@exputo.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class MoodleRepository extends Repository {
    private $store;

    /**
     * Constructs a new Repository.
     * @param object $store
     */
    public function __construct($store) {
        $this->store = $store;
    }

    /**
     * Reads an array of objects from the store with the given type and query.
     * @param string $type
     * @param array $query 
     * @return array
     */
    public function read_records(string $type, array $query) {
        return $this->store->get_records($type, $query);
    }

    /**
     * Reads an object from the store with the given type and query.
     * @param string $type
     * @param array $query
     * @throws \Exception if the record was not found
     * @return PhpObj
     */
    public function read_record(string $type, array $query) {
        $record = $this->store->get_record($type, $query);
        if ($record === false) {
            throw new \Exception("$type not found.");
        }
        return $record;
    }
}
