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

defined('MOODLE_INTERNAL') || die();

define('XAPI_REPORT_ID_ERROR', 0);
define('XAPI_REPORT_ID_HISTORIC', 1);

// Type constants.
define('XAPI_IMPORT_TYPE_LIVE', 0);
define('XAPI_IMPORT_TYPE_HISTORIC', 1);
define('XAPI_IMPORT_TYPE_RECONCILE', 2);

// Report source.
define('XAPI_REPORT_SOURCE_FAILED', 'logstore_xapi_failed_log');
define('XAPI_REPORT_SOURCE_HISTORICAL', 'logstore_standard_log');

/**
 * Get all visible cohorts in the system.
 *
 * @return array Returns an array of all visible cohorts.
 */
function logstore_xapi_get_cohorts() {
    global $DB;
    $array = array("visible" => 1);
    $cohorts = $DB->get_records("cohort", $array);
    return $cohorts;
}

/**
 * Get the selected cohorts from the settings.
 *
 * @return array Returns an array of selected cohort ids if the cohort is still visible.
 * The cohort might have been made invisible or removed since the selection was made.
 */
function logstore_xapi_get_selected_cohorts() {
    $arrvisible = logstore_xapi_get_cohorts();
    $selected = get_config('logstore_xapi', 'cohorts');

    $arrselected = explode(",", $selected);
    $arr = array();
    foreach ($arrselected as $arrselection) {
        if (array_key_exists($arrselection, $arrvisible)) {
            $arr[] = $arrselection;
        }
    }
    return $arr;
}

/**
 * Return all members for a cohort
 *
 * @param array $cohortids array of cohort ids
 * @return array with cohort id keys containing arrays of user email addresses
 */
function logstore_xapi_get_cohort_members($cohortids) {
    global $DB;

    $members = array();

    foreach ($cohortids as $cohortid) {
        // Validate params.
        $cohort = $DB->get_record('cohort', array('id' => $cohortid), '*', MUST_EXIST);
        if (!empty($cohort)) {
            $cohortmembers = $DB->get_records_sql("SELECT u.id, u.email
                FROM {user} u, {cohort_members} cm
                WHERE u.id = cm.userid AND cm.cohortid = ?
                ORDER BY lastname ASC, firstname ASC", array($cohort->id));
            $emailaddresses = array();
            foreach ($cohortmembers as $member) {
                $emailaddresses[] = $member->email;
            }
            $members[] = array('cohortid' => $cohortid, 'emails' => $emailaddresses);
        }
    }
    return $members;
}

/**
 * Get the selected cohorts from the settings.
 *
 * @return array Returns an array of distinct email addresses from cohorts and additional email addresses.
 */
function logstore_xapi_distinct_email_addresses() {
    $arr = array();

    // ensure no duplicates in csv
    $emailaddresses = get_config('logstore_xapi', 'send_additional_email_addresses');
    $arrselected = explode(",", $emailaddresses);
    foreach ($arrselected as $arrselection) {
        if (!in_array($arrselection, $arr)) {
            $arr[] = $arrselection;
        }
    }

    // get selected cohorts
    $cohorts = logstore_xapi_get_selected_cohorts();
    $cohortswithmembers = logstore_xapi_get_cohort_members($cohorts);

    // add to the list again ensuring no duplicates
    foreach ($cohortswithmembers as $cohort) {
        foreach ($cohort["emails"] as $email) {
            if (!in_array($email, $arr)) {
                $arr[] = $email;
            }
        }
    }

    // sort it for logging purposes later
    sort($arr);
    return $arr;
}

/**
 * Gets the unique column values
 *
 * @param $column
 * @return array
 * @throws dml_exception
 */
function logstore_xapi_get_distinct_options_from_failed_table($column) {
    global $DB;

    $options = [0 => get_string('any')];
    $results = $DB->get_fieldset_select('logstore_xapi_failed_log', "DISTINCT $column", '');
    if ($results) {
        // The array keys by default are numbered, here we will assign the values to the keys
        $results = array_combine($results, $results);
        $options = array_merge($options, $results);
    }
    return $options;
}

/**
 * Get the available context's from the logstore standard log table
 *
 * @return array
 * @throws dml_exception
 */
function logstore_xapi_get_logstore_standard_context_options() {
    global $DB;

    $options = [0 => get_string('any')];

    $sql = 'SELECT DISTINCT(contextid)
              FROM {logstore_standard_log}';
    $contextids = array_keys($DB->get_records_sql($sql));

    foreach ($contextids as $contextid) {
        $context = context::instance_by_id($contextid);
        $options[$context->id] = $context->get_context_name();
    }

    return $options;
}

/**
 * Retrieves the available and enabled events for this plugin and outputs it into an array
 *
 * @return array
 */
function logstore_xapi_get_event_names_array() {

    if (!function_exists('\src\transformer\get_event_function_map')) {
        global $CFG;

        require_once($CFG->dirroot . '/admin/tool/log/store/xapi/src/transformer/get_event_function_map.php');
    }

    $eventnames = [];
    $eventfunctionmap = \src\transformer\get_event_function_map();
    foreach (array_keys($eventfunctionmap) as $eventname) {
        $eventnames[$eventname] = $eventname;
    }
    return $eventnames;
}


/**
 * Decode the json array stored in the response column. Will return false if json is invalid
 *
 * @param $response
 * @return array|bool
 */
function logstore_xapi_decode_response($response) {
    $decode = json_decode($response, true);
    // Check JSON is valid
    if (json_last_error() === JSON_ERROR_NONE) {
        return $decode;
    }
    return false;
}

/**
 * Generate the string for the info column in the report
 *
 * @param $row
 * @return string
 * @throws coding_exception
 */
function logstore_xapi_get_info_string($row) {
    if (!empty($row->errortype)) {
        $response = '-';
        if (isset($row->response)) {
            $decode = logstore_xapi_decode_response($row->response);
            if ($decode) {
                $response = $decode;
            }
        }
        switch ($row->errortype) {
            case 101:
                return get_string('networkerror', 'logstore_xapi', $response);
            case 400:
                // Recipe issue
                return get_string('recipeerror', 'logstore_xapi', $response);
            case 401:
                // Unauthorised, could be an issue with xAPI credentials
                return get_string('autherror', 'logstore_xapi', $response);
            case 500:
                // xAPI server error
                return get_string('lrserror', 'logstore_xapi', $response);
            default:
                // Generic error catch all
                return get_string('unknownerror', 'logstore_xapi', ['errortype' => $row->errortype, 'response' => $response]);
                break;
        }
    }
    return ''; // Return blank if no errortype captured
}

/**
 * Get successful events.
 *
 * @param $events events
 * @return array
 */
function get_successful_events($events) {
    $loadedevents = array_filter($events, function ($loadedevent) {
        return $loadedevent['loaded'] === true;
    });
    $successfulevents = array_map(function ($loadedevent) {
        return $loadedevent['event'];
    }, $loadedevents);
    return $successfulevents;
}

/**
 * Take event data and add to the sent log if it doesn't exist already.
 *
 * @param array $event raw event data
 */
function add_event_to_sent_log($event) {
    global $DB;

    $row = $DB->get_record('logstore_xapi_sent_log', ['logstorestandardlogid' => $event->logstorestandardlogid]);
    if (empty($row)) {
        $newrow = new stdClass();
        $newrow->logstorestandardlogid = $event->logstorestandardlogid;
        $newrow->type = $event->type;
        $newrow->timecreated = time();
        $DB->insert_record('logstore_xapi_sent_log', $newrow);
    }
}
