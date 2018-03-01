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

require_once(__DIR__ . '/../../src/autoload.php');
use \tool_log\log\writer as log_writer;
use \tool_log\log\manager as log_manager;
use \tool_log\helper\store as helper_store;
use \tool_log\helper\reader as helper_reader;
use \tool_log\helper\buffered_writer as helper_writer;
use \core\event\base as event_base;
use \stdClass as php_obj;

/**
 * This class processes events and enables them to be sent to a logstore.
 *
 */
class store extends php_obj implements log_writer {
    use helper_store;
    use helper_reader;
    use helper_writer;

    protected $loggingenabled = false;

    /** @var bool $logguests true if logging guest access */
    protected $logguests;

    /** @var array $routes An array of routes to include */
    protected $routes = [];

    /**
     * Constructs a new store.
     * @param log_manager $manager
     */
    public function __construct(log_manager $manager) {
        global $CFG;
        $this->helper_setup($manager);
        $this->logguests = $this->get_config('logguests', 1);
        $routes = $this->get_config('routes', '');
        $this->routes = $routes === '' ? [] : explode(',', $routes);
        if (!empty($CFG->debug) and $CFG->debug >= DEBUG_DEVELOPER) {
            $this->loggingenabled = true;
        }
    }

    /**
     * Should the event be ignored (not logged)? Overrides helper_writer.
     * @param event_base $event
     * @return bool
     *
     */
    protected function is_event_ignored(event_base $event) {
        if ((!CLI_SCRIPT || PHPUNIT_TEST) && !$this->logguests && isguestuser()) {
            // Always log inside CLI scripts because we do not login there.
            return true;
        }

        if (!in_array($event->eventname, $this->routes)) {
            // Ignore event if the store settings do not want to store it.
            return true;
        }
        return false;
    }

    /**
     * Insert events in bulk to the database. Overrides helper_writer.
     * @param array $events raw event data
     *
     */
    protected function insert_event_entries(array $events) {
        global $DB;

        // If in background mode, just save them in the database.
        if ($this->get_config('backgroundmode', false)) {
            $DB->insert_records('logstore_xapi_log', $events);
        } else {
            $this->process_events($events);
        }
    }

    public function process_events(array $events) {
        global $DB;
        global $CFG;
        $handler_config = [
            'transformer' => [
                'source_url' => 'http://moodle.org',
                'source_name' => 'Moodle',
                'source_version' => '1.0.0',
                'source_lang' => 'en',
                'send_mbox' => $this->get_config('mbox', false),
                'plugin_url' => 'http://www.example.org/plugin',
                'plugin_version' => '1.0.0',
                'repo' => new moodle_repository($DB, $CFG),
            ],
            'loader' => [
                'loader' => 'log',
                'lrs_endpoint' => $this->get_config('endpoint', ''),
                'lrs_username' => $this->get_config('username', ''),
                'lrs_password' => $this->get_config('password', ''),
                'lrs_max_batch_size' => $this->get_config('maxbatchsize', 100),
            ],
        ];
        \src\handler($handler_config, $events);
    }

    /**
     * Determines if a connection exists to the store.
     * @return boolean
     */
    public function is_logging() {
        return true;
    }
}
