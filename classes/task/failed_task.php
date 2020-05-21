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

namespace logstore_xapi\task;
defined('MOODLE_INTERNAL') || die();

use tool_log\log\manager;
use logstore_xapi\log\store;
use stdClass;

class failed_task extends \core\task\scheduled_task {

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('taskfailed', 'logstore_xapi');
    }

    /**
     * Extract events from logstore_xapi_log or logstore_xapi_failed_log.
     *
     * @return array
     */
    private function extract_events($limitnum=0, $type='log') {
        global $DB;

        $conditions = null;
        $sort = '';
        $fields = '*';
        $limitfrom = 0;

        if ($type == 'log') {
            $events = $DB->get_records('logstore_xapi_log', $conditions, $sort, $fields, $limitfrom, $limitnum);
        } else {
            $events = $DB->get_records('logstore_xapi_failed_log', $conditions, $sort, $fields, $limitfrom, $limitnum);
        }
        return $events;
    }

    /**
     * Insert events into logstore_xapi_log.
     *
     * @return none
     */
    private function insert_failed_events_into_xapi_log($events) {
        global $DB;

        $DB->insert_records("logstore_xapi_log", $events);
    }

    /**
     * Delete events from logstore_xapi_failed_log.
     *
     * @return none
     */
    private function delete_failed_events($events) {
        global $DB;

        $eventids = $this->get_delete_event_ids($events);
        $DB->delete_records_list('logstore_xapi_failed_log', 'id', $eventids);
    }

    /**
     * Get failed events and add in errortype and response.
     *
     * @return array
     */
    private function get_failed_events($events) {
        $nonloadedevents = array_filter($events, function ($loadedevent) {
            return $loadedevent['loaded'] === false;
        });
        $failedevents = array_map(function ($nonloadedevent) {
            return $nonloadedevent['event'];
        }, $nonloadedevents);
        return $failedevents;
    }

    /**
     * Store failed events in logstore_xapi_failed_log.
     *
     * @return none
     */
    private function store_failed_events($events) {
        global $DB;

        $failedevents = $this->get_failed_events($events);
        $DB->insert_records('logstore_xapi_failed_log', $failedevents);
        mtrace(count($failedevents) . " " . get_string('failed_events', 'logstore_xapi'));
    }

    /**
     * Display count of successful events.
     *
     * @return none
     */
    private function record_successful_events($events) {
        mtrace(count(get_successful_events($events)) . " " . get_string('successful_events', 'logstore_xapi'));
    }

    /**
     * Take successful events and save each using add_event_to_sent_log.
     *
     * @param array $events raw events data
     */
    private function save_sent_events(array $events) {
        $successfulevents = get_successful_events($events);
        foreach ($successfulevents as $event) {
            add_event_to_sent_log($event);
        }
    }

    /**
     * Get event ids.
     *
     * @return array
     */
    private function get_event_ids($loadedevents) {
        return array_map(function ($loadedevent) {
            return $loadedevent['event']->id;
        }, $loadedevents);
    }

    /**
     * Get deletion event ids.
     * Do not use array_map because it can throw an exception.
     *
     * @return array
     */
    private function get_delete_event_ids($loadedevents) {
        $arr = array();
        foreach ($loadedevents as $event) {
            $arr[] = $event->id;
        }
        return $arr;
    }

    /**
     * Delete processed events in logstore_xapi_log.
     *
     * @return none
     */
    private function delete_processed_events($events) {
        global $DB;
        $eventids = $this->get_event_ids($events);
        $DB->delete_records_list('logstore_xapi_log', 'id', $eventids);
    }

    /**
     * Do the job.
     * Throw exceptions on errors (the job will be retried).
     */
    public function execute() {
        $manager = get_log_manager();
        $store = new store($manager);

        // Copy failed events back into the xapi log and
        // deleted the failed events from the failed log.
        $extractedfailedevents = $this->extract_events($store->get_max_batch_size(), 'failed');
        $this->insert_failed_events_into_xapi_log($extractedfailedevents);
        $this->delete_failed_events($extractedfailedevents);

        // Re-run as normal.
        $extractedevents = $this->extract_events($store->get_max_batch_size());
        $loadedevents = $store->process_events($extractedevents);
        $this->store_failed_events($loadedevents);
        $this->record_successful_events($loadedevents);
        $this->save_sent_events($loadedevents);
        $this->delete_processed_events($loadedevents);

        echo "In failed task execute".PHP_EOL;
    }
}
