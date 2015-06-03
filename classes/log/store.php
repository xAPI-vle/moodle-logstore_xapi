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
 * Standard log reader/writer.
 *
 * @package    logstore_xapi
 * @copyright  2015 Jerrett Fowler {@link http://charitylearning.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace logstore_xapi\log;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/admin/tool/log/store/xapi/locallib.php');
spl_autoload_register(function($class){
    global $CFG;
    $verbs = $CFG->dirroot . '/admin/tool/log/store/xapi/classes/xapi/verbs/';
    foreach(glob($verbs . "*.php") as $file) {
        require_once($file);
    }
});

class store implements \tool_log\log\writer, \core\log\sql_internal_reader {
    use \tool_log\helper\store,
        \tool_log\helper\reader,
        \tool_log\helper\buffered_writer;

    protected $lrs;
    protected $endpoint;
    protected $username;
    protected $password;

    public function __construct(\tool_log\log\manager $manager) {
        $this->helper_setup($manager);
        $this->endpoint = $this->get_config('endpoint', '');
        $this->username = $this->get_config('username', '');
        $this->password = $this->get_config('password', '');
    }

    protected function init() {
        //Set up the connection to the LRS for testing purposes
        if (isset($this->endpoint)) {
            return !empty($this->endpoint);
        }
        try {
            $lrs = lrs_connect($endpoint, $username, $password);
            //Connect and test if LRS is available for sending
        } catch (\moodle_exception $e) {
            debugging('Cannot connect to LRS: ' . $e->getMessage(), DEBUG_DEVELOPER);
            return false;
        }
        return true;
    }

    /**
     * Should the event be ignored (== not logged)?
     * @param \core\event\base $event
     * @return bool
     */
    protected function is_event_ignored(\core\event\base $event) {
        $verbs_available = $this->get_verb_classes();
        foreach($verbs_available as $verb) {
            if($this->validate_logstore_eventname($verb, $event->eventname)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Insert events in bulk to the database.
     *
     * @param array $evententries raw event data
     */
    protected function insert_event_entries($evententries) {
        //This is where it will send to LRS
        echo '<pre>', print_r($evententries, true), '</pre>';
        if (!$this->is_logging()) {
            return;
        }
        try {
            //Send xAPI statement to LRS
            foreach($evententries as $entry) {
                $verb_class = $this->get_verb_object($entry['action']);
                if($verb_class){
                    //create statement with new class
                    $response = \logstore_xapi\set_record($entry, $verb_class->getID());
                    //if($response->code == '400') //error
                    //if($response->code == '200') //error
                }
            }
        } catch (\moodle_exception $e) {
            debugging('Cannot write to LRS: ' . $e->getMessage(), DEBUG_DEVELOPER);
        }
    }

    public function get_events_select($selectwhere, array $params, $sort, $limitfrom, $limitnum) {
        //Return events from the LRS given the parameters.
        return false;
    }

    public function get_events_select_count($selectwhere, array $params) {
        //Return a count of events in the LRS given the parameters.
        return false;
    }

    public function get_internal_log_table_name() {
        return false;
    }

    /**
     * Are the new events appearing in the reader?
     *
     * @return bool true means new log events are being added, false means no new data will be added
     */
    public function is_logging() {
        if (!$this->init()) {
            return false;
        }
        return true;
    }

    /**
     * Get a list of available verbs.
     */
    protected function get_verb_classes() {
        global $CFG;
        $classes = array();
        $basepath = $CFG->dirroot . "/admin/tool/log/store/xapi/classes/xapi/verbs/";
        foreach(glob($basepath . "*.php") as $file) {
            $classes[] = $file;
        }
        return $classes;
    }

    /**
     * Return a verb object if verb exists.
     */
    protected function get_verb_object($newverb) {
        if(is_string($newverb) && strpos($newverb,'.php') !== false){
            $newverb = basename($newverb, ".php");
        }
        if(is_object($newverb)){
            return $newverb;
        }
        $availableverbs = $this->get_verb_classes();
        foreach ($availableverbs as $verb) {
            $verb = basename($verb, ".php");
            if(!class_exists($verb)) {
                if($verb == $newverb) {
                    $new_verb_class = "logstore_xapi\\xapi\\verbs\\" . $newverb;
                    return new $new_verb_class;
                }
            }
        }
        return false;
    }

    /**
     * Check if the logstore event name matches the allowed event names within the verb sub classes.
     */
    protected function validate_logstore_eventname($verbclass, $eventname) {
        $class = $this->get_verb_object($verbclass);
        if($class) {
            $allowed_event_name = $class->getEventName($eventname);
            if($eventname == $allowed_event_name){
                return true;
            }
        }
        return false;
    }
}
