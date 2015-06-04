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

/**
 * Logstore emitter.
 * @package logstore_emitter
 * @copyright 2015 Jerrett Fowler {@link http://charitylearning.org}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace logstore_emitter\log;
defined('MOODLE_INTERNAL') || die();

require_once __DIR__ . '/../../vendor/autoload.php';
use \tool_log\log\writer as log_writer;
use \tool_log\log\manager as log_manager;
use \tool_log\helper\store as helper_store;
use \tool_log\helper\reader as helper_reader;
use \tool_log\helper\buffered_writer as helper_writer;
use \core\event\base as event_base;
use \logstore_emitter\xapi\service as xapi_service;
use \logstore_emitter\xapi\repository as xapi_repository;
use \logstore_emitter\moodle\service as moodle_service;
use \logstore_emitter\moodle\repository as moodle_repository;
use \moodle_exception as moodle_exception;
use \stdClass as php_obj;

class store extends php_obj implements log_writer {
    use helper_store;
    use helper_reader;
    use helper_writer;

    /**
     * Constructs a new store.
     * @param log_manager $manager
     */
    public function __construct(log_manager $manager) {
        $this->helper_setup($manager);
    }

    /**
     * Should the event be ignored (not logged)?
     * @param event_base $event
     * @return bool
     * @override helper_writer
     */
    protected function is_event_ignored(event_base $event) {
        return isset(xapi_service::$action_to_recipe[$event['eventname']]);
    }

    /**
     * Insert events in bulk to the database.
     * @param array $evententries raw event data
     * @override helper_writer
     */
    protected function insert_event_entries(array $events) {
        echo '<pre>', print_r($evententries, true), '</pre>';

        // Initializes required services.
        $xapi_service = new xapi_service($this->connect_xapi_repository());
        $moodle_service = new moodle_service($this->connect_moodle_repository());

        // Emits events to other APIs.
        foreach($events as $event) {
            $moodle_event = $this->moodle_service->create($entry);
            $xapi_statement = $this->xapi_service->create($moodle_event);
        }
    }

    /**
     * Determines if a connection exists to the store.
     * @return boolean
     */
    public function is_logging() {
        try {
            $this->connect_xapi_repository();
            return true;
        } catch (moodle_exception $ex) {
            debugging('Cannot connect to LRS: ' . $e->getMessage(), DEBUG_DEVELOPER);
            return false;
        }
    }

    /**
     * Creates a connection the xAPI store.
     * @return xapi_repository
     */
    private function connect_xapi_repository() {
        return new xapi_repository(
            $this->get_config('endpoint', ''),
            $this->get_config('username', ''),
            $this->get_config('password', '')
        );
    }

    /**
     * Creates a connection the xAPI store.
     * @return moodle_repository
     */
    private function connect_moodle_repository() {
        global $DB;
        return new moodle_repository($DB);
    }
}
