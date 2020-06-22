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

use stdClass;
use tool_log\log\manager;
use logstore_xapi\log\store;

class emit_task extends \core\task\scheduled_task {

    /** @var int $batchsize - Batch size for sending tasks to LRS */
    protected $batchsize;

    /** @var int $type - The import type we are targeting to send */
    protected $type = XAPI_IMPORT_TYPE_LIVE;

    /**
     * emit_task constructor.
     */
    public function __construct() {
        $manager = get_log_manager();
        $store = new store($manager);

        $this->batchsize = $store->get_max_batch_size();
    }

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('taskemit', 'logstore_xapi');
    }

    /**
     * Get failed events as array.
     *
     * @param $events
     * @return array
     */
    protected function get_failed_events($events) {
        $nonloadedevents = array_filter($events, function ($loadedevent) {
            return $loadedevent['loaded'] === false;
        });
        $failedevents = array_map(function ($nonloadedevent) {
            return $nonloadedevent['event'];
        }, $nonloadedevents);
        return $failedevents;
    }

    /**
     * Get successful events.
     *
     * @param $events
     * @return array
     */
    protected function get_successful_events($events) {
        $loadedevents = array_filter($events, function($loadedevent) {
            return $loadedevent['loaded'] === true;
        });
        $successfulevents = array_map(function($loadedevent) {
            return $loadedevent['event'];
        }, $loadedevents);
        return $successfulevents;
    }

    /**
     * Get event ids.
     *
     * @param $loadedevents
     * @return array
     */
    protected function get_event_ids($loadedevents) {
        return array_map(function ($loadedevent) {
            return $loadedevent['event']->id;
        }, $loadedevents);
    }

    /**
     * Extract events from logstore_xapi_log or logstore_xapi_failed_log.
     *
     * @param int $limitnum limit number
     * @param string $log log source
     * @param int $type event type
     * @return array
     * @throws \dml_exception
     */
    protected function extract_events($limitnum = 0, $log = XAPI_REPORT_SOURCE_LOG) {
        global $DB;

        $conditions = array('type' => $this->type);
        $sort = '';
        $fields = '*';
        $limitfrom = 0;

        $extractedevents = $DB->get_records($log, $conditions, $sort, $fields, $limitfrom, $limitnum);
        return $extractedevents;
    }

    /**
     * Delete processed events.
     *
     * @param array $events raw events data
     */
    protected function delete_processed_events($events) {
        global $DB;
        $eventids = $this->get_event_ids($events);
        $DB->delete_records_list('logstore_xapi_log', 'id', $eventids);
    }

    /**
     * Store failed events in logstore_xapi_failed_log.
     *
     * @param $events
     * @return void
     */
    protected function store_failed_events($events) {
        global $DB;

        $failedevents = $this->get_failed_events($events);
        $DB->insert_records('logstore_xapi_failed_log', $failedevents);
        mtrace(count($failedevents) . " " . get_string('failed_events', 'logstore_xapi'));
    }

    /**
     * Log the number of events using mtrace.
     *
     * @param array $events raw events data
     */
    protected function record_successful_events($events) {
        mtrace(count($this->get_successful_events($events)) . " " . get_string('successful_events', 'logstore_xapi'));
    }

    /**
     * Take successful events and save each using logstore_xapi_add_event_to_sent_log.
     *
     * @param array $events raw events data
     */
    protected function save_sent_events(array $events) {
        $successfulevents = $this->get_successful_events($events);
        foreach ($successfulevents as $event) {
            $this->add_event_to_sent_log($event);
        }
    }

    /**
     * Take event data and add to the sent log if it doesn't exist already.
     *
     * @param array $event raw event data
     */
    protected function add_event_to_sent_log($event) {
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

    /**
     * Do the job.
     * Throw exceptions on errors (the job will be retried).
     */
    public function execute() {
        $manager = get_log_manager();
        $store = new store($manager);

        $extractedevents = $this->extract_events($this->batchsize);
        $loadedevents = $store->process_events($extractedevents);

        $this->store_failed_events($loadedevents);
        $this->record_successful_events($loadedevents);
        $this->save_sent_events($loadedevents);
        $this->delete_processed_events($loadedevents);
    }
}
