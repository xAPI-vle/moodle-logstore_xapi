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
     * Insert events into logstore_xapi_log.
     *
     * @return none
     */
    private function insert_failed_events_into_xapi_log($events) {
        global $DB;

        $DB->insert_records("logstore_xapi_log", $events);
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
     * Do the job.
     * Throw exceptions on errors (the job will be retried).
     */
    public function execute() {
        $manager = get_log_manager();
        $store = new store($manager);
        $batchsize = $store->get_max_batch_size_for_failed();

        // Copy failed events back into the xapi log and
        // deleted the failed events from the failed log.
        $extractedfailedevents = logstore_xapi_extract_events($batchsize, XAPI_REPORT_SOURCE_FAILED);
        $this->insert_failed_events_into_xapi_log($extractedfailedevents);
        $this->delete_failed_events($extractedfailedevents);

        // Re-run as normal.
        $extractedevents = logstore_xapi_extract_events($batchsize);
        $loadedevents = $store->process_events($extractedevents);
 
        logstore_xapi_store_failed_events($loadedevents);
        logstore_xapi_record_successful_events($loadedevents);
        logstore_xapi_save_sent_events($loadedevents);
        logstore_xapi_delete_processed_events($loadedevents);

        echo "In failed task execute".PHP_EOL;
    }
}
