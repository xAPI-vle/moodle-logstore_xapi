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
 * External xapi log store plugin
 *
 * @package    logstore_xapi
 * @copyright  2015 Jerrett Fowler <jfowler@charitylearning.org>
 *                  Ryan Smith <ryan.smith@ht2.co.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace logstore_xapi\log;
defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../vendor/autoload.php');
use \tool_log\log\writer as log_writer;
use \tool_log\log\manager as log_manager;
use \tool_log\helper\store as helper_store;
use \tool_log\helper\reader as helper_reader;
use \tool_log\helper\buffered_writer as helper_writer;
use \core\event\base as event_base;
use \XREmitter\Controller as xapi_controller;
use \XREmitter\Repository as xapi_repository;
use \MXTranslator\Controller as translator_controller;
use \LogExpander\Controller as moodle_controller;
use \LogExpander\Repository as moodle_repository;
use \TinCan\RemoteLRS as tincan_remote_lrs;
use \moodle_exception as moodle_exception;
use \stdClass as php_obj;

/**
 * This class processes events and enables them to be sent to a logstore.
 *
 */
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
     * Should the event be ignored (not logged)? Overrides helper_writer.
     * @param event_base $event
     * @return bool
     *
     */
    protected function is_event_ignored(event_base $event) {
        return false;
    }

    /**
     * Insert events in bulk to the database. Overrides helper_writer.
     * @param array $events raw event data
     *
     */
    protected function insert_event_entries(array $events) {
        global $DB;

        // If in background mode, just save them in the database
        if (get_config('logstore_xapi', 'backgroundmode')) {
            $DB->insert_records('logstore_xapi_log', $events);
        } else {
            $this->process_events($events);
        }

    }

    public function process_events(array $events) {

        // Initializes required services.
        $xapicontroller = new xapi_controller($this->connect_xapi_repository());
        $moodlecontroller = new moodle_controller($this->connect_moodle_repository());
        $translatorcontroller = new translator_controller();

        // Emits events to other APIs.
        foreach ($events as $event) {
            $event = (array) $event;
            // $this->error_log('');
            // $this->error_log_value('event', $event);
            $moodleevent = $moodlecontroller->createEvent($event);
            if (is_null($moodleevent)) {
                continue;
            }

            // $this->error_log_value('moodleevent', $moodleevent);
            $translatorevent = $translatorcontroller->createEvent($moodleevent);
            if (is_null($translatorevent)) {
                continue;
            }

            // $this->error_log_value('translatorevent', $translatorevent);
            $xapievent = $xapicontroller->createEvent($translatorevent);
            // $this->error_log_value('xapievent', $xapievent);
        }
    }

    private function error_log_value($key, $value) {
        $this->error_log('['.$key.'] '.json_encode($value));
    }

    private function error_log($message) {
        error_log($message."\r\n", 3, __DIR__.'/error_log.txt');
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
        return new xapi_repository(new tincan_remote_lrs(
            $this->get_config('endpoint', ''),
            '1.0.1',
            $this->get_config('username', ''),
            $this->get_config('password', '')
        ));
    }

    /**
     * Creates a connection the xAPI store.
     * @return moodle_repository
     */
    private function connect_moodle_repository() {
        global $DB;
        global $CFG;
        return new moodle_repository($DB, $CFG);
    }
}
